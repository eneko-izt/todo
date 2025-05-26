<?php

namespace App\Http\Controllers;

use App\Column;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumnsController extends Controller
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
        $columns = Column::withCount('tasks')->paginate(10);

        return view('columns.index', compact('columns'));
    }

    public function trash()
    {
        $columns = Column::onlyTrashed()->paginate(10);

        return view('columns.trash', compact('columns'));
    }

    public function create()
    {
        $title = 'New column';
        $button = 'Create';
        $route = route('columns.store');
        $column = new Column();
        
       return view('columns.form', compact('title', 'button', 'route', 'column'));
    }

    public function store()
    {
        $this->validateColumnCreate();

        $column = new Column(request(['name', 'colour']));
        $column->active = request('active') == 'on' ? 1 : 0;

        $column->save();

        return redirect(route("columns.index"));
    }

    public function edit($id)
    {
        $title = 'Edit column';
        $button = 'Save';
        $route = route('columns.update', $id);
        $column = Column::findOrFail($id);

        return view('columns.form', compact('title', 'button', 'route', 'column'));
    }

    public function update($id)
    {

        $column = Column::findOrFail($id);

        $column->name = request('name');
        $column->colour = request('colour');
        $column->active = request('active') == 'on' ? 1 : 0;

        $this->validateColumnUpdate($id);

        $column->save();

        return redirect(route("columns.index"));
    }

    public function delete($id)
    {
        $column = Column::findOrFail($id);

        if ($column->tasks()->count() > 0) {
            return redirect(route("columns.index"))->with('error', 'You cannot delete this column because it has tasks.');
        }

        $column->delete();

        return redirect(route("columns.index"));
    }

    public function restore($id)
    {
        $column = Column::withTrashed()->findOrFail($id);
        $column->restore();
        return redirect(route("columns.trash"));
    }

    protected function validateColumnCreate()
    {
        return request()->validate([
            'name' => ['required', 'unique:columns', 'max:255',],
            'colour' => 'required|max:10'
        ]);
    }

    protected function validateColumnUpdate($id)
    {
        return request()->validate([
            'name' => ['required', 'max:255', \Illuminate\Validation\Rule::unique('columns')->ignore($id)],
            'colour' => 'required|max:10'
        ]);
    }
}
