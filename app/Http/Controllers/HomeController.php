<?php

namespace App\Http\Controllers;

use App\Column;
use App\Tag;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('home', compact('columns', 'tags'));
    }
}
