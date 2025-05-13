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
        $columns = Column::simplePaginate(10);

        //TODO: kontsulta errepikatuak daude, abisatu ta azaltzen dizut
        return view('columns', ['columns' => $columns]); //TODO: sinplifikatzeko, parametroak pasatzeko bistetara egin dezakezu compact('columns')
    }
}
