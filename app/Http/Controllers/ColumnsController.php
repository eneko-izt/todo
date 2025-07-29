<?php

namespace App\Http\Controllers;

use App\Column;

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
        $policy = 'createColumn';
        $route = route('columns.store');
        $routeMethod = 'POST';
        $column = new Column();

        return view('columns.form', compact('title', 'button', 'policy', 'route', 'routeMethod', 'column'));
    }

    public function store()
    {
        $this->validateColumn();
        $column = $this->fillRequestData();
        $column->save();
        return redirect(route("columns.index"));
    }

    public function edit($id)
    {
        $title = 'Edit column';
        $button = 'Save';
        $policy = 'editColumn';
        $route = route('columns.update', $id);
        $routeMethod = 'PATCH';
        $column = Column::findOrFail($id);

        return view('columns.form', compact('title', 'button', 'policy', 'route', 'routeMethod', 'column'));
    }

    public function update($id)
    {
        $column = Column::findOrFail($id);
        $this->fillRequestData($column);
        $this->validateColumn($id);
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

    public function destroy($id)
    {
        $column = Column::withTrashed()->findOrFail($id);

        $column->forceDelete();

        return redirect(route("columns.trash"));
    }

    private function validateColumn($id = null)
    {
        // default validation rules
        $nameValidations = ['required', 'max:255'];
        $colourValidations = ['required', 'max:10'];

        $extraNameValidation = $id ? \Illuminate\Validation\Rule::unique('columns')->ignore($id) : 'unique:columns';
        array_push($nameValidations, $extraNameValidation);

        return request()->validate([
            'name' => $nameValidations,
            'colour' => $colourValidations
        ]);
    }

    private function fillRequestData($column = null)
    {
        if (!$column) {
            $column = new Column();
        }

        $column->name = request('name');
        $column->colour = request('colour');
        $column->active = request('active') == 'on' ? 1 : 0;

        return $column;
    }
}
