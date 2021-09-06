<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class,'index']);
    Route::post('/store', [CategoryController::class,'store']);
    Route::put('/update', [CategoryController::class,'update']);
    Route::delete('/destroy/{id}', [CategoryController::class,'destroy']);
});


Route::group(['prefix' => 'posts'], function () {
    Route::get('/', [PostController::class,'index']);
    Route::post('/store', [PostController::class,'store']);
    Route::put('/update', [PostController::class,'update']);
    Route::delete('/destroy/{id}', [PostController::class,'destroy']);
});
