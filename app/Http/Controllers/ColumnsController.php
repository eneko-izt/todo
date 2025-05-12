<?php

namespace App\Http\Controllers;

use App\Column;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
        $columns = Column::simplePaginate(1);
        return view('columns', ['columns' => $columns]);
    }

}
