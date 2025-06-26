<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class UsersController extends Controller
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
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $title = 'New user';
        $button = 'Create';
        $route = route('users.store');
        $routeMethod = 'POST';
        $user = new User();
        $roles = Role::all();

        return view('users.form', compact('title', 'button', 'route', 'routeMethod', 'user', 'roles'));
    }

    public function store()
    {
        $this->validateUserCreate();

        $user = new User(request(['name', 'email']));
        $user->password = bcrypt(request('password'));
        $user->active = request('active') == 'on' ? 1 : 0;

        $roles['roles'] = request('roles', []);
        if (count($roles['roles']) > 0) 
        {
            $validator = Validator::make($roles, ['roles' => 'required|exists:roles,id']);
            if ($validator->fails()) {return redirect()->back()->withErrors($validator)->withInput();}
        }
        $user->save();

        if (count($roles['roles']) > 0) 
        {
            $user->roles()->attach($roles['roles']);
        }
    
        return redirect(route("users.index"));
    }

    public function edit($id)
    {
        $title = 'Edit user';
        $button = 'Save';
        $route = route('users.update', $id);
        $routeMethod = 'PATCH';
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('users.form', compact('title', 'button', 'route', 'routeMethod', 'user', 'roles'));
    }

    public function update($id)
    {
        $user = User::findOrFail($id);

        $this->validateUserUpdate($id);

        $user->name = request('name');
        $user->email = request('email');
        if (request('password') <> null)
        {
            $user->password = bcrypt(request('password'));
        }
        $user->active = request('active') == 'on' ? 1 : 0;

        $roles['roles'] = request('roles', []);
        if (count($roles['roles']) > 0) 
        {
            $validator = Validator::make($roles, ['roles' => 'required|exists:roles,id']);
            if ($validator->fails()) {return redirect()->back()->withErrors($validator)->withInput();}
        }   

        $user->save();

        foreach ($user->roles as $role) 
        {
            // If the role is not in the new roles, we mark it as deleted
            if (!in_array($role->id, $roles['roles'])) 
            {
                $user->roles()->updateExistingPivot($role->id, ['deleted_at' => now()]);
            }
        }

        foreach ($roles['roles'] as $role) 
        {
            if ($user->roleswithtrashed()->where('role_user.role_id', $role)->exists()) 
            {
                // If the role already exists, we just update the deleted_at field
                $user->roles()->updateExistingPivot($role, ['deleted_at' => null]);
                continue;
            }
            else 
            {
                // If the role does not exist, we attach it
                $user->roles()->attach($role);
            }
        }

        return redirect(route("users.index"));
    }

    protected function validateUserCreate()
    {
        return request()->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'max:255'],
        ]);
    }

    protected function validateUserUpdate($id)
    {
        return request()->validate([
            'name' => ['required', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($id)],
            'email' => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($id)],
            'password' => ['confirmed', 'max:255'],
        ]);
    }
}
