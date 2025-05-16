<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/columns', 'ColumnsController@index')->name('columns.index');
Route::post('/columns', 'ColumnsController@store')->name('columns.store');
Route::get('/columns/trash', 'ColumnsController@trash')->name('columns.trash');
Route::get('/columns/create', 'ColumnsController@create')->name('columns.create');
//TODO: rutak ordenatu controllerean bezala index, transh, create, store, edit, update.... horrela errezagoa da bilatzea