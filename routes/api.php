<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegisterController;
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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::prefix('/v1')->group( function () {
    Route::post('/register', [ 'as' => 'register', 'uses' => 'App\Http\Controllers\API\RegisterController@register']);
    Route::post('/login', [ 'as' => 'login', 'uses' => 'App\Http\Controllers\API\RegisterController@login']);

    Route::middleware('auth:api')->group( function () {
        Route::resource('/products', ProductController::class);
        Route::prefix('/products')->name('products.')->group( function () {
        });
        Route::prefix('/logout')->name('logout')->group( function () {
            Route::any('/','App\Http\Controllers\API\RegisterController@logout');
            Route::post('/all',[ 'as' => '.all', 'uses' => 'App\Http\Controllers\API\RegisterController@allLogout']);
        });
    });
});



