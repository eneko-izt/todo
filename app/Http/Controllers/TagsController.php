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

    public function create()
    {
        $title = 'New tag';
        $button = 'Create';
        $route = route('tags.store');
        $routeMethod = 'POST';
        $tag = new Tag();
        
       return view('tags.form', compact('title', 'button', 'route', 'routeMethod', 'tag'));
    }

    public function store()
    {
        $this->validateTagCreate();

        $tag = new Tag(request(['name', 'colour']));
        $tag->active = request('active') == 'on' ? 1 : 0;

        $tag->save();

        return redirect(route("tags.index"));
    }

    protected function validateTagCreate()
    {
        return request()->validate([
            'name' => ['required', 'unique:tags', 'max:255',],
            'colour' => 'required|max:10'
        ]);
    }
}
