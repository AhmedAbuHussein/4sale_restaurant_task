<?php

use App\Http\Controllers\Api\V1\TableController;
use App\Http\Controllers\Api\V1\TableReservationController;
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

    Route::get("check/available", [TableReservationController::class, 'check_available']);
    Route::apiResource("tables", TableController::class)->only(['index', 'show']);

    // Route::apiResource("reservation", ReservationController::class);
    // Route::apiResource("meals", ReservationController::class);
    // Route::apiResource("orders", ReservationController::class);

    Route::group(['middleware'=> ['auth:api']], function(){
        Route::get('user', [UserController::class, 'index']);
    });
});
