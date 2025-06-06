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

        $attributes = $this->validateTaskCreate(request('column_id'));

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

    protected function validateTaskCreate($column_id)
    {
        return request()->validate(
        [
            'text'.$column_id => ['required', 'max:255',],
            'order'.$column_id => 'required|numeric|min:0|max:100',
            'column_id' => 'required|exists:columns,id'
        ],
        [
            'text'.$column_id.'.required' => 'The task text is required.',
            'text'.$column_id.'.max' => 'The task text may not be greater than 255 characters.',
            'order'.$column_id.'.required' => 'The order is required.',
            'order'.$column_id.'.numeric' => 'The order must be a number.',
            'order'.$column_id.'.min' => 'The order must be at least 0.',
            'order'.$column_id.'.max' => 'The order may not be greater than 100.'
        ]);
    }
}
