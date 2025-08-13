<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use App\Column;

class HomeController extends Controller
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
        $columns = Column::where('active', true)->get();
        $tags = Tag::active()->get();
        $users = User::active()->where('id', '!=', auth()->id())->get();

        return view('home', compact('columns', 'tags', 'users'));
    }
}
