<?php

namespace App\Http\Controllers;

use App\Tag;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
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
        $tags = Tag::withCount('tasks')->paginate(10);

        return view('tags.index', compact('tags'));
    }

    public function trash()
    {
        $tags = Tag::onlyTrashed()->paginate(10);
        return view('tags.trash', compact('tags'));
    }
}
