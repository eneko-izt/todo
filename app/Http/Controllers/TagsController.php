<?php

namespace App\Http\Controllers;

use App\Tag;

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
        $tags = Tag::withCount('tasks')->get();

        return view('tags.index', compact('tags'));
    }

    public function trash()
    {
        $tags = Tag::onlyTrashed()->get();

        return view('tags.trash', compact('tags'));
    }

    public function create()
    {
        $title = 'New tag';
        $button = 'Create';
        $policy = 'createTag';
        $route = route('tags.store');
        $routeMethod = 'POST';
        $tag = new Tag();

        return view('tags.form', compact('title', 'button', 'policy', 'route', 'routeMethod', 'tag'));
    }

    public function store()
    {
        $this->validateTag();
        $tag = $this->fillRequestData();
        $tag->save();
        return redirect(route("tags.index"));
    }

    public function edit($id)
    {
        $title = 'Edit tag';
        $button = 'Save';
        $policy = 'editTag';
        $route = route('tags.update', $id);
        $routeMethod = 'PATCH';
        $tag = Tag::findOrFail($id);

        return view('tags.form', compact('title', 'button', 'policy', 'route', 'routeMethod', 'tag'));
    }

    public function update($id)
    {
        $tag = Tag::findOrFail($id);
        $this->fillRequestData($tag);
        $this->validateTag($id);
        $tag->save();
        return redirect(route("tags.index"));
    }

    public function delete($id)
    {
        $tag = Tag::findOrFail($id);

        if ($tag->tasks()->count() > 0) {
            return redirect(route("tags.index"))->with('error', 'You cannot delete this tag because it has tasks.');
        }

        $tag->delete();

        return redirect(route("tags.index"));
    }

    public function restore($id)
    {
        $tag = Tag::withTrashed()->findOrFail($id);
        $tag->restore();

        return redirect(route("tags.trash"));
    }

    public function destroy($id)
    {
        $tag = Tag::withTrashed()->findOrFail($id);

        $tag->forceDelete();

        return redirect(route("tags.trash"));
    }

    protected function validateTag($id = null)
    {
        // default validation rules
        $nameValidations = ['required', 'max:255'];
        $colourValidations = ['required', 'max:10'];

        $extraNameValidation = $id ? \Illuminate\Validation\Rule::unique('tags')->ignore($id) : 'unique:tags';
        array_push($nameValidations, $extraNameValidation);

        return request()->validate([
            'name' => $nameValidations,
            'colour' => $colourValidations
        ]);
    }

    private function fillRequestData($tag = null)
    {
        if (!$tag) {
            $tag = new Tag();
        }

        $tag->name = request('name');
        $tag->colour = request('colour');
        $tag->active = request('active') == 'on' ? 1 : 0;

        return $tag;
    }
}
