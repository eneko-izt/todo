<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserService
{
    public function validateUser($id = null)
    {
        // Build the validation rules dynamically
        $rules = [
            'name' => ['required', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                $id
                    ? Rule::unique('users')->ignore($id)
                    : 'unique:users',
            ],
            'password' => ['max:255', 'confirmed'],
            'roles' => ['exists:roles,id'],
        ];

        // If we're creating a new user, password is required
        if (!$id) {
            $rules['password'][] = 'required';
        }

        // Make the validator instance
        $validator = Validator::make(request()->all(), $rules);

        // Validate or throw
        if ($validator->fails()) {
            // Automatically throws a ValidationException like request()->validate()
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    public function fillUser($user)
    {
        $user->name = request('name');
        $user->email = request('email');
        $user->active = request('active') == 'on' ? 1 : 0;

        if (request()->filled('password')) {
            $user->password = bcrypt(request('password'));
        }

        return $user;
    }
}
