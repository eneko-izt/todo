<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use App\Column;

use App\Http\Services\CacheService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->middleware('auth');
        $this->cacheService = $cacheService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $columns = $this->cacheService->activeColumns();
        $tags = $this->cacheService->activeTags();

        return view('home2', compact('columns', 'tags'));
    }
}
