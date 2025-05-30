<?php

namespace App\Http\Controllers;

use App\Task;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
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

     
    public function activate($id, bool $value)
    {
        $task = Task::findOrFail($id);
        $task->active = $value ? 1 : 0;
        $task->save();

        return redirect(route("home"));
    }
}