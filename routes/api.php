<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\ProductApiController;
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

Route::controller(UserApiController::class)->prefix('user')->group(function (){
    Route::post('login','login')->middleware(['throttle:3,10']); //rate limiter 3hit/10min
    Route::post('register','register')->middleware(['throttle:3,10']); //rate limiter 3hit/10min
});

Route::controller(ProductApiController::class)->prefix('product')->group(function (){
    Route::get('list','list');
    Route::post('single-details','singleDetails');
    Route::post('create','create');
    Route::delete('delete','destroy');
});



//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
