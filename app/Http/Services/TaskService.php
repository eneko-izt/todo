<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;

class TaskService
{

    public function createValidator($requestData)
    {
        $rules = $this->getValidationRules();
        $requestData += ["user_id" => auth()->id()];
        return Validator::make($requestData, $rules);
    }

    public function updateValidator($requestData, $task)
    {
        $rules = $this->getValidationRules($task);
        $requestData += ["user_id" => auth()->id()];
        return Validator::make($requestData, $rules);
    }

    private function getValidationRules($task = null)
    {
        $userValidation = ['required', 'exists:users,id'];

        if ($task != null) {
            $extraUserValidation = function ($attribute, $value, $fail) use ($task) {
                if ($task->user_id != $value) {
                    $fail(__('You do not have permission to update this task.'));
                }
            };

            array_push($userValidation, $extraUserValidation);
        }

        $rules = [
            'text' => ['required', 'max:255'],
            'order' => ['required', 'numeric', 'min:0', 'max:100'],
            'column_id' => ['required', 'exists:columns,id'],
            'user_id' => $userValidation,
            'tags' => ['exists:tags,id']
        ];

        return $rules;
    }
}