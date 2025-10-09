<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;

class UserService
{
    public function validateUser($id = null)
    {
        // default validation rules
        $nameValidations = ['required', 'max:255'];
        $emailValidations = ['required', 'email', 'max:255'];

        // if an ID is provided, email must be unique but for that user
        // otherwise, unique email validation is applied
        $extraEmailValidation = $id ? \Illuminate\Validation\Rule::unique('users')->ignore($id) : 'unique:users';
        array_push($emailValidations, $extraEmailValidation);

        // request()->merge([
        //     'roles' => ['3']
        // ]);
        return request()->validate([
            'name' => $nameValidations,
            'email' => $emailValidations,
            'password' => ['confirmed', 'max:255'],
            'roles' => ['exists:roles,id']
        ]);
    }

    public function fillUser($user)
    {
        $user->name = request('name');
        $user->email = request('email');
        $user->active = request('active') == 'on' ? 1 : 0;
        $user->language = request('language');

        if (request()->has('password')) {
            $user->password = bcrypt(request('password'));
        }

        return $user;
    }
}
