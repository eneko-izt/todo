<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $title = 'New user';
        $button = 'Create';
        $route = route('users.store');
        $routeMethod = 'POST';
        $user = new User();

        return view('users.form', compact('title', 'button', 'route', 'routeMethod', 'user'));
    }

    public function store()
    {
        $this->validateUserCreate();

        $user = new User(request(['name', 'active', 'email']));
        $user->active = request('active') == 'on' ? 1 : 0;
        //$user->password = bcrypt(Str::random(8));
        $user->password = bcrypt('password'); // Default password, can be changed later

        $user->save();

        return redirect(route("users.index"));
    }

    public function edit($id)
    {
        $title = 'Edit user';
        $button = 'Save';
        $route = route('users.update', $id);
        $routeMethod = 'PATCH';
        $user = User::findOrFail($id);

        return view('users.form', compact('title', 'button', 'route', 'routeMethod', 'user'));
    }

    public function update($id)
    {
        $user = User::findOrFail($id);

        $user->name = request('name');
        $user->email = request('email');
        $user->active = request('active') == 'on' ? 1 : 0;

        $this->validateUserUpdate($id);

        $user->save();

        return redirect(route("users.index"));
    }

    protected function validateUserCreate()
    {
        return request()->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users']
        ]);
    }

    protected function validateUserUpdate($id)
    {
        return request()->validate([
            'name' => ['required', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($id)],
            'email' => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($id)]
        ]);
    }
}
