<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', 'App\Http\Controllers\CategoryController@index');

// Route::get('/', function () {
//     return view('app');
// });

//this route must be always bellow any route
Route::get('{path}', function () {
    return view('welcome');
})->where('path', '(.*)');


// use App\Http\Controllers\UserController;

// Route::get('/users', [UserController::class, 'index']);
// // or
// Route::get('/users', 'App\Http\Controllers\UserController@index');