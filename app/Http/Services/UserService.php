<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;

class UserService
{
    public function processUser($id = null)
    {
        if ($id) 
        {
            $this->validateUserUpdate($id);
            $user = \App\User::findOrFail($id);
        }
        else
        {
            $this->validateUserCreate();
            $user = new \App\User();
        }

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

    private function validateUserCreate()
    {
        return request()->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'max:255'],
        ]);
    }

    private function validateUserUpdate($id)
    {
        return request()->validate([
            'name' => ['required', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($id)],
            'email' => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($id)],
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
