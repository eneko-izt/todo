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
        return $this->loadEditView(new Column([
            'name' => '',
            'colour' => '',
            'active' => 1
        ]));
    }

    public function store()
    {
        $this->validateColumn();

        $column = new Column(request(['name', 'colour']));
        $column->active = request('active') == 'on' ? 1 : 0;

        $column->save();

        return redirect(route("columns.index"));
    }

    public function edit($id)
    {
        return $this->loadEditView(Column::findOrFail($id));
    }

    public function update()
    {
        $this->validateColumn(true);

        // $column = new Column();
        // $column->name = request('name');
        // $column->colour = request('colour');
        // $column->active = request('active') == 'on' ? 1 : 0;

        // $column->save();

        return redirect(route("columns.index"));
    }


    protected function loadEditView(Column $column)
    {
        return view('columns.edit')->withColumn($column);
    }

    protected function validateColumn(bool $update = false)
    {
        $array = [
            'name' => ['required', 'max:255'],
            'colour' => 'required|max:10'
        ];

        if (!$update) {
            $array['name'][] = 'unique:columns';
        }

        return request()->validate($array);
    }
}
