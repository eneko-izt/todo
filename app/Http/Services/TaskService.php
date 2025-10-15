<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;

class TaskService
{

    public function createValidator($requestData)
    {
        $columnId = $requestData['column_id'];
        $rules = $this->getValidationRules($columnId);
        $requestData += [("user_id") => auth()->id()];
        return Validator::make($requestData, $rules);
    }

    public function updateValidator($requestData, $task)
    {
        $rules = $this->getValidationRules($task->id, $task);
        $requestData += [("user_id") => auth()->id()];
        return Validator::make($requestData, $rules);
    }

    private function getValidationRules($id, $task = null)
    {
        $columnEntry = 'column_id';
        $userValidation = ['required', 'exists:users,id'];

        if ($task != null) {
            // If a task is provided it means we are updating it. In this case column id is suffixed by the task id
            // Else, when creating a new task column id cannot be suffixed.
            // These are UI requirements to avoid having input fields with same names.
            $columnEntry = 'column_id' . $task->id;

            $extraUserValidation = function ($attribute, $value, $fail) use ($task) {
                if ($task->user_id != $value) {
                    $fail('You do not have permission to update this task.');
                }
            };

            array_push($userValidation, $extraUserValidation);
        }

        $rules = [
            'text' => ['required', 'max:255'],
            'order' => ['required', 'numeric', 'min:0', 'max:100'],
            $columnEntry => ['required', 'exists:columns,id'],
            'user_id' => $userValidation,
            'tags' => ['exists:tags,id']
        ];

        return $rules;
    }
}