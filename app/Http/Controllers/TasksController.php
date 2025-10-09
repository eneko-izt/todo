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
            $attributes['order'] = request('order' . $attributes['column_id'], 0);
            $attributes['text'] = request('text' . $attributes['column_id']);

            DB::beginTransaction();

            $task = Task::create($attributes);

            $tags = request('tags' . $attributes['column_id'], []);
            $task->tags()->attach($tags);

            DB::commit();

            return redirect(route("home"));

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('modal_id', 'newTaskModal-' . request('column_id'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['creation_error' => 'An error occurred while creating the task'])
                ->withInput()
                ->with('modal_id', 'newTaskModal-' . request('column_id'));
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
                ->withInput()
                ->with('modal_id', 'staticBackdrop-' . $task->id);
        }

        try {
            DB::beginTransaction();
            // update task
            $task->text = request('text' . $id);
            $task->order = request('order' . $id);
            $task->column_id = request('column_id' . $id);
            $task->save();

            // update tags
            $tags = request('tags' . $id, []);
            $task->tags()->sync($tags);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withErrors(['update_error' => 'An error occurred while updating task ' . $id])
                ->withInput()
                ->with('modal_id', 'staticBackdrop-' . $task->id);
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
                    ->withErrors(['email' => 'An error occurred while sharing task ' . $taskId . ' with user ' . $user->name])
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
}
