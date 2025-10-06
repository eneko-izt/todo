<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use App\Mail\TaskSharedMail;

use App\Http\Services\TaskService;
use Illuminate\Support\Facades\Mail;

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


    public function index()
    {
        $this->authorize('adminAllTasks', Task::class);

        return view('tasks.index');
    }

    public function getData()
    {
        $this->authorize('adminAllTasks', Task::class);

        if (!request()->ajax()) {
                return abort(403);
        }

        // DataTables parameters
        $draw = intval(request()->input('draw', 1));
        $start = intval(request()->input('start', 0));
        $length = intval(request()->input('length', 10));
        $search = request()->input('search.value', null);
        $orderColumnIndex = request()->input('order.0.column', 0);
        $orderDir = request()->input('order.0.dir', 'asc');

        // Map column index from JS to database columns (or relationship columns)
        $columns = [
            0 => 'column_name', // mapped later
            1 => 'text',
            2 => 'tags',        // will handle filtering separately
            3 => 'user_name',   // mapped later
            4 => 'sharing_users' // optional, handle via filtering
        ];

        if (request()->ajax()) {
            $query = Task::with(['column', 'user', 'tags', 'sharingUsers'])->select(['id', 'text', 'column_id', 'user_id']);

            // Global search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('text', 'like', "%$search%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%$search%"))
                    ->orWhereHas('column', fn($q2) => $q2->where('name', 'like', "%$search%"))
                    ->orWhereHas('tags', fn($q2) => $q2->where('name', 'like', "%$search%"))
                    ->orWhereHas('sharingUsers', fn($q2) => $q2->where('name', 'like', "%$search%"));
                });
            }

            // Total records after filtering
            $recordsFiltered = $query->count();

            // Total records in table (without filtering)
            $recordsTotal = Task::count();

            // Ordering (for simplicity, order by main table columns or related name via join)
            if (isset($columns[$orderColumnIndex])) {
                $orderColumn = $columns[$orderColumnIndex];

                // For relationships like column_name or user_name, order via join
                if ($orderColumn === 'column_name') {
                    $query->join('columns', 'tasks.column_id', '=', 'columns.id')
                        ->orderBy('columns.name', $orderDir)
                        ->select('tasks.*'); // prevent column conflicts
                } elseif ($orderColumn === 'user_name') {
                    $query->join('users', 'tasks.user_id', '=', 'users.id')
                        ->orderBy('users.name', $orderDir)
                        ->select('tasks.*');
                } else {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            // Pagination
            $tasks = $query->skip($start)->take($length)->get();

            // Build final data array for DataTables
            $dataArray = $tasks->map(function($task) {
                return [
                    "column_name" => $task->column->name ?? '-',
                    "text" => $task->text ?? '-',
                    "tags" => implode(", ", $task->tags->pluck('name')->toArray()),
                    "user_name" => $task->user->name ?? '-',
                    "sharing_users" => implode(", ", $task->sharingUsers->pluck('name')->toArray())
                ];
            });

            // Return JSON response for DataTables
            return response()->json([
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $dataArray
            ]);
        }
    }

    public function store()
    {
        $this->taskService->createValidator(request()->all())->validate();

        $attributes = [];
        $attributes['active'] = 1;
        $attributes['user_id'] = auth()->id();
        $attributes['column_id'] = request('column_id');
        $attributes['order'] = request('order' . $attributes['column_id'], 0);
        $attributes['text'] = request('text' . $attributes['column_id']);

        $task = Task::create($attributes);

        $tags = request('tags' . $attributes['column_id'], []);
        $task->tags()->attach($tags);

        return redirect(route("home"));
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
                ->withInput()
                ->with('modal_id', 'staticBackdrop-' . $task->id);
        }

        $task->text = request('text' . $id);
        $task->active = request('active' . $id) == 'on' ? 1 : 0;
        $task->order = request('order' . $id);
        $task->column_id = request('column_id' . $id);
        $task->save();

        // update tags
        $tags = request('tags' . $id, []);
        $task->tags()->sync($tags);

        return redirect(route("home"));
    }

    public function share($taskId)
    {
        $task = Task::findOrFail($taskId);
        $user = User::findOrFail(request('userid'));

        $this->authorize('shareTask', $task);

        if (! $task->sharingUsers()->where('user_id', $user->id)->exists())
        {
            $task->sharingUsers()->attach($user->id);

            Mail::to($user->email)->queue(new TaskSharedMail($user, $task));
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
}
