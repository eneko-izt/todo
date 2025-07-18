<?php

namespace App\Http\Controllers;

use App\Task;

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
        $this->createValidator(request()->all())->validate();

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

        $validator = $this->updateValidator(request()->all(), $task);
        if ($validator->fails())
        {
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
        $tags = request('tags'.$id, []);
        $task->tags()->sync($tags);

        return redirect(route("home"));
    }

    private function createValidator($requestData)
    {
        $columnId = $requestData['column_id'];
        $rules = $this->getValidationRules($columnId);
        $requestData += [("user_id"  . $columnId)=> auth()->id()];
        return Validator::make($requestData, $rules);
    }

    private function updateValidator($requestData, $task)
    {
        $rules = $this->getValidationRules($task->id, $task);
        $requestData += [("user_id"  . $task->id)=> auth()->id()];
        return Validator::make($requestData, $rules);
    }

    private function getValidationRules($id, $task = null)
    {
        $columnEntry = 'column_id';
        $userValidation = ['required', 'exists:users,id'];

        if ($task != null)
        {
            $columnEntry = 'column_id' . $task->id;

            $extraUserValidation = function ($attribute, $value, $fail) use ($task)
                {
                    if ($task->user_id != $value) 
                    {
                        $fail('You do not have permission to update this task.');
                    }
                };

            array_push($userValidation, $extraUserValidation);
        }

        $rules = [
            'text'.$id => ['required', 'max:255'],
            'order'.$id => ['required', 'numeric', 'min:0', 'max:100'],
            $columnEntry => ['required', 'exists:columns,id'],
            'user_id'.$id => $userValidation,
            'tags'.$id => ['exists:tags,id']
        ];

        return $rules;

        // [
        //     'text.*.required' => 'The task text is required.',
        //     'text.*.max' => 'The task text may not be greater than 255 characters.',
        //     'order.*.required' => 'The order is required.',
        //     'order.*.numeric' => 'The order must be a number.',
        //     'order.*.min' => 'The order must be at least 0.',
        //     'order.*.max' => 'The order may not be greater than 100.'
        // ]
        // );
    }
}
