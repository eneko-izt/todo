<?php

namespace App\Http\Controllers;

use App\Task;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TasksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function store()
    {
        $attributes = $this->validateTask(request('column_id'));

        $data = ['user_id' => auth()->id()];
        $validator =Validator::make($data, ['user_id' => 'required|exists:users,id']);
        if ($validator->fails()) { return redirect()->back()->withErrors($validator)->withInput();}

        $tags['tags'] = request('tags', []);
        $validator = Validator::make($tags, ['tags' => 'required|exists:tags,id']);
        if ($validator->fails()) {return redirect()->back()->withErrors($validator)->withInput();}

        $attributes['active'] = 1;
        $attributes['user_id'] = auth()->id();
        $attributes['column_id'] = request('column_id');
        $attributes['order'] = request('order'.$attributes['column_id'], 0);
        $attributes['text'] = request('text'.$attributes['column_id']);

        $task = Task::create($attributes);
        $task->tags()->attach($tags['tags']);

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

        $this->validateTask($id);

        // $tag->name = request('name');
        // $tag->colour = request('colour');
        // $tag->active = request('active') == 'on' ? 1 : 0;

        // $this->validateTagUpdate($id);

        // $tag->save();

        return redirect(route("home"));
    }

    protected function validateTask($id)
    {
        //dd(request()->all());
        return request()->validate(
        [
            'text'.$id => ['required', 'max:2',],
            'order'.$id => 'required|numeric|min:0|max:100',
            'column_id' => 'required|exists:columns,id'
        ]//,
        // [
        //     'text.*.required' => 'The task text is required.',
        //     'text.*.max' => 'The task text may not be greater than 255 characters.',
        //     'order.*.required' => 'The order is required.',
        //     'order.*.numeric' => 'The order must be a number.',
        //     'order.*.min' => 'The order must be at least 0.',
        //     'order.*.max' => 'The order may not be greater than 100.'
        // ]
        );
    }
}
