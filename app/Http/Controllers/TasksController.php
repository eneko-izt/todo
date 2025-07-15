<?php

namespace App\Http\Controllers;

use App\Task;
//TODO: erabiltzen ez direnak ezabatu
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($data, ['user_id' => 'required|exists:users,id']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tags['tags'] = request('tags', []);
        $validator = Validator::make($tags, ['tags' => 'exists:tags,id']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $attributes['active'] = 1;
        $attributes['user_id'] = auth()->id();
        $attributes['column_id'] = request('column_id');
        $attributes['order'] = request('order' . $attributes['column_id'], 0);
        $attributes['text'] = request('text' . $attributes['column_id']);

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

        $rules = [
            'text'.$id => ['required', 'max:255',],
            'order'.$id => 'required|numeric|min:0|max:100',
            'column_id'.$id => 'required|exists:columns,id'
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) 
        {
            // dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal_id', 'staticBackdrop-' . $task->id);
        }

        // user validation
        $data = ['user_id' => auth()->id()];
        $rules = ['user_id' => ['required', 'exists:users,id', function ($attribute, $value, $fail) use ($task)
            {
                if ($task->user_id != $value) 
                {
                    $fail('You do not have permission to update this task.');
                }
            }
            ]
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails())
        { 
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal_id', 'staticBackdrop-' . $task->id);
        }
        
        $tags['tags'] = request('tags'.$id, []);
        $validator = Validator::make($tags, ['tags'.$id => 'exists:tags,id']);
        if ($validator->fails()) 
        {
            // dd($validator->errors());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal_id', 'staticBackdrop-' . $task->id);
        }


        $task->text = request('text'.$id);
        $task->active = request('active'.$id) == 'on' ? 1 : 0;
        $task->order = request('order'.$id);
        $task->column_id = request('column_id'.$id);
        $task->save();

        // update tags
        $task->tags()->sync($tags['tags']);

        return redirect(route("home"));
    }

    protected function validateTask($id)
    {
        //dd(request()->all());
        return request()->validate(
        [
            'text'.$id => ['required', 'max:255',],
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
