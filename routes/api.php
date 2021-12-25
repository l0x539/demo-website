<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace('App\Http\Controllers')->group(function () {
    Route::prefix('/subscriptions')->group(function () {
        Route::get('/', 'SubscriptionController@index');
        Route::get('/{id}', 'SubscriptionController@show');
        Route::post('/add', 'SubscriptionController@store');
    });
    Route::prefix('/posts')->group(function () {
        Route::get('/', 'PostsController@index');
        Route::get('/{id}', 'PostsController@show');
        Route::post('/add', 'PostsController@store');
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
