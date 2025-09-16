<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use App\Column;

use App\Http\Services\RepoCacheService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $repoCacheService;

    public function __construct(RepoCacheService $repoCacheService)
    {
        $this->middleware('auth');
        $this->repoCacheService = $repoCacheService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $columns = $this->repoCacheService->activeColumns();
        $tags = $this->repoCacheService->activeTags();

        return view('home', compact('columns', 'tags'));
    }
}
