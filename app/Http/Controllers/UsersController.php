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
        $users = User::withCount('tasks')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function trash()
    {
    }

    public function create()
    {
    }

    public function store()
    {
    }

    public function edit($id)
    {
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
