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

/*
|--------------------------------------------------------------------------
| Home Routes and Task Management
|--------------------------------------------------------------------------
*/

Route::get('/home', 'HomeController@index')->name('home');

Route::delete('/tasks/delete/{id}', 'TasksController@delete')->name('tasks.delete');
Route::post('/tasks', 'TasksController@store')->name('tasks.store');
Route::patch('/tasks/{id}', 'TasksController@update')->name('tasks.update');
Route::patch('/tasks/share/{id}', 'TasksController@share')->name('tasks.share');
Route::patch('/tasks/unshare/{id}/user/{userId}', 'TasksController@unshare')->name('tasks.unshare');

Route::post('/tasks/upload/{id}', 'FileController@store')->name('tasks.upload');
Route::get('/tasks/download/{id}', 'FileController@download')->name('tasks.download');

Route::get('/tasks', 'TasksController@index')->name('tasks.index')->middleware('can:adminAllTasks, App\Task');

/*
|--------------------------------------------------------------------------
| Column Management
|--------------------------------------------------------------------------
*/

Route::get('/columns', 'ColumnsController@index')->name('columns.index')->middleware('can:viewColumn, App\Column');

Route::middleware('can:createColumn, App\Column')->group(function () {
    Route::get('/columns/create', 'ColumnsController@create')->name('columns.create');
    Route::post('/columns', 'ColumnsController@store')->name('columns.store');
});

Route::middleware('can:editColumn, App\Column')->group(function () {
    Route::get('/columns/edit/{id}', 'ColumnsController@edit')->name('columns.edit');
    Route::patch('/columns/{id}', 'ColumnsController@update')->name('columns.update');
});

Route::middleware('can:deleteColumn, App\Column')->group(function () {
    Route::get('/columns/trash', 'ColumnsController@trash')->name('columns.trash');
    Route::delete('/columns/delete/{id}', 'ColumnsController@delete')->name('columns.delete');
    Route::patch('/columns/restore/{id}', 'ColumnsController@restore')->name('columns.restore');
    Route::delete('/columns/destroy/{id}', 'ColumnsController@destroy')->name('columns.destroy');
});

/*
|--------------------------------------------------------------------------
| Tag Management
|--------------------------------------------------------------------------
*/

Route::get('/tags', 'TagsController@index')->name('tags.index')->middleware('can:viewTag, App\Tag');

Route::middleware('can:deleteTag, App\Tag')->group(function () {
    Route::get('/tags/trash', 'TagsController@trash')->name('tags.trash');
    Route::delete('/tags/delete/{id}', 'TagsController@delete')->name('tags.delete');
    Route::patch('/tags/restore/{id}', 'TagsController@restore')->name('tags.restore');
    Route::delete('/tags/destroy/{id}', 'TagsController@destroy')->name('tags.destroy');
});

Route::middleware('can:createTag, App\Tag')->group(function () {
    Route::get('/tags/create', 'TagsController@create')->name('tags.create');
    Route::post('/tags', 'TagsController@store')->name('tags.store');
});

Route::middleware('can:editTag, App\Tag')->group(function () {
    Route::get('/tags/edit/{id}', 'TagsController@edit')->name('tags.edit');
    Route::patch('/tags/{id}', 'TagsController@update')->name('tags.update');
});

/*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
*/

Route::get('/users', 'UsersController@index')->name('users.index')->middleware('can:viewUser, App\User');

Route::middleware('can:createUser, App\User')->group(function () {
    Route::get('/users/create', 'UsersController@create')->name('users.create');
    Route::post('/users', 'UsersController@store')->name('users.store');
});

Route::middleware('can:editUser, App\User')->group(function () {
    Route::get('/users/edit/{id}', 'UsersController@edit')->name('users.edit');
    Route::patch('/users/{id}', 'UsersController@update')->name('users.update');
});
