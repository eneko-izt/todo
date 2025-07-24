<?php

namespace App\Http\Controllers;

use App\Task;
use App\Http\Services\TaskService;

use Illuminate\Support\Facades\Validator;

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

        $task->delete();

        return redirect(route("home"));
    }

    public function update($id)
    {
        $task = Task::findOrFail($id);

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
}
