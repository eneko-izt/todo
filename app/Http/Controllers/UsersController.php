<?php

namespace App\Http\Controllers;

use App\User;

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
    }

    public function delete($id)
    {
    }

    public function restore($id)
    {
    }

    public function destroy($id)
    {
    }
}
