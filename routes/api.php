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

Route::group(['prefix'=> "v1.0"], function(){
    Route::apiResource("tables", \App\Http\Controllers\Api\V1\TableController::class);
    Route::apiResource("reservation", ReservationController::class);
    Route::apiResource("meals", ReservationController::class);
    Route::apiResource("orders", ReservationController::class);

    Route::group(['middleware'=> ['auth:api']], function(){
        Route::get('user', [UserController::class, 'index']);
    });
});
