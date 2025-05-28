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
Route::get('/columns/trash', 'ColumnsController@trash')->name('columns.trash');
Route::get('/columns/create', 'ColumnsController@create')->name('columns.create');
Route::post('/columns', 'ColumnsController@store')->name('columns.store');
Route::get('/columns/edit/{id}', 'ColumnsController@edit')->name('columns.edit');
Route::patch('/columns/{id}', 'ColumnsController@update')->name('columns.update');
Route::delete('/columns/delete/{id}', 'ColumnsController@delete')->name('columns.delete');
Route::patch('/columns/restore/{id}', 'ColumnsController@restore')->name('columns.restore');
Route::delete('/columns/destroy/{id}', 'ColumnsController@destroy')->name('columns.destroy');

Route::get('/tags', 'TagsController@index')->name('tags.index');
Route::get('/tags/trash', 'TagsController@trash')->name('tags.trash');
