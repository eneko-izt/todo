<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;

class UserService
{
    public function processUser($id = null)
    {
        $this->validateUserEmailPassword($id);
        $user = $id ? \App\User::findOrFail($id) : new \App\User();

        $user = $this->fillUser($user);
        $user->save();
        
        return $user;
    }

    private function fillUser($user)
    {
        $user->name = request('name');
        $user->email = request('email');
        $user->active = request('active') == 'on' ? 1 : 0;

        if (request()->has('password')) {
            $user->password = bcrypt(request('password'));
        }

        return $user;
    }

    private function validateUserEmailPassword($id = null)
    {
        // default validation rules
        $nameValidations = ['required', 'max:255'];
        $emailValidations = ['required', 'email', 'max:255'];

        // if an ID is provided, email must be unique but for that user
        // otherwise, unique email validation is applied
        $extraEmailValidation = $id ? \Illuminate\Validation\Rule::unique('users')->ignore($id) : 'unique:users';
        array_push($emailValidations, $extraEmailValidation);

        return request()->validate([
            'name' => $nameValidations,
            'email' => $emailValidations,
            'password' => ['confirmed', 'max:255'],
        ]);
    }

    public function validateRoles()
    {
        $roles['roles'] = request('roles', []);
        if (count($roles['roles']) > 0) 
        {
            $validator = Validator::make($roles, ['roles' => 'required|exists:roles,id']);
            if ($validator->fails()) {return redirect()->back()->withErrors($validator)->withInput();}
        }

        return $roles;
    }
}
