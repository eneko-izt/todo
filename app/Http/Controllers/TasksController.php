<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Exception;

use App\Mail\TaskSharedMail;
use App\Http\Services\TaskService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * The task service instance.
     *
     * @var TaskService
     */
    private $taskService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TaskService $taskService)
    {
        $this->middleware('auth');
        $this->taskService = $taskService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function store()
    {
        try {
            $this->taskService->createValidator(request()->all())->validate();

            $attributes = [];
            $attributes['active'] = 1;
            $attributes['user_id'] = auth()->id();
            $attributes['column_id'] = request('column_id');
            $attributes['order'] = request('order', 0);
            $attributes['text'] = request('text');

            DB::beginTransaction();

            $task = Task::create($attributes);

            $tags = request('tags', []);
            $task->tags()->attach($tags);

            DB::commit();

            return redirect(route("home"));

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['db_error' => __('An error occurred while creating the task.')])
                ->withInput();
        }
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);

        $this->authorize('deleteTask', $task);

        $task->delete();

        return redirect(route("home"));
    }

    public function update($id)
    {
        $task = Task::findOrFail($id);

        $this->authorize('editTask', $task);

        $validator = $this->taskService->updateValidator(request()->all(), $task);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            // update task
            $task->text = request('text');
            $task->order = request('order');
            $task->column_id = request('column_id');
            $task->save();

            // update tags
            $tags = request('tags', []);
            $task->tags()->sync($tags);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withErrors(['db_error' => __('An error occurred while updating the task.')])
                ->withInput();
        }

        return redirect(route("home"));
    }

    public function share($taskId)
    {
        $task = Task::findOrFail($taskId);
        $user = User::findOrFail(request('userid'));

        $this->authorize('shareTask', $task);

        if (! $task->sharingUsers()->where('user_id', $user->id)->exists())
        {
            try {
                DB::beginTransaction();
                Mail::to($user->email)->queue(new TaskSharedMail($user, $task));
                $task->sharingUsers()->attach($user->id);
                DB::commit();
            }
            catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['email' => __('An error occurred while sharing the task.')])
                    ->withInput();
            }
        }

        return redirect(route("home"));
    }

    public function unshare($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);

        $this->authorize('shareTask', $task);

        $task->sharingUsers()->detach($userId);

        return redirect(route("home"));
    }

    public function index()
    {
        $this->authorize('viewAllTasks', Task::class);
        return view('tasks.index');
    }

    public function getAllTasks(Request $request)
    {
        $this->authorize('viewAllTasks', Task::class);

        // Only columns that can be safely ordered
        $columns = ['text', 'user_name', 'column_name'];

        // Base query with joins for ordering related columns
        $query = Task::with(['tags', 'sharingUsers'])
            ->leftJoin('users', 'tasks.user_id', '=', 'users.id')
            ->leftJoin('columns', 'tasks.column_id', '=', 'columns.id')
            ->select('tasks.*')  // important to avoid ambiguity
            ->where('tasks.active', 1);

        // Searching
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('tasks.text', 'like', "%{$search}%")
                ->orWhere('users.name', 'like', "%{$search}%")
                ->orWhere('columns.name', 'like', "%{$search}%")
                ->orWhereHas('sharingUsers', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('tags', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Count before filtering
        $recordsTotal = Task::active()->count();
        $recordsFiltered = $query->count();

        // Ordering
        if ($order = $request->input('order.0')) {
            $columnIndex = $order['column'];
            $direction = $order['dir'];

            // Map columnIndex to actual column
            switch ($columnIndex) {
                case 0: // column
                    $query->orderBy('columns.name', $direction);
                    break;
                case 1: // text
                    $query->orderBy('tasks.text', $direction);
                    break;
                case 2: // owner
                    $query->orderBy('users.name', $direction);
                    break;
                default:
                    $query->orderBy('users.name', 'asc');
            }
        } else {
            $query->orderBy('users.name', 'asc');
        }

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $tasks = $query->skip($start)->take($length)->get();

        // Format Data for DataTables
        $data = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'text' => $task->text,
                'owner' => $task->user ? $task->user->name : null,
                'column' => $task->column ? $task->column->name : null,
                'tags' => $task->tags->pluck('name')->join(', '),
                'sharingUsers' => $task->sharingUsers->pluck('name')->join(', '),
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
