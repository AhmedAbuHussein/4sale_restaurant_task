<?php

use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ReservationController;
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
    Route::post('login', [LoginController::class, 'login']);

    Route::apiResource("reservations", ReservationController::class)->only(['store', 'show']);
    Route::apiResource("availables", TableReservationController::class)->only(['index', 'show']);
    Route::apiResource("tables", TableController::class)->only(['index', 'show']);
    Route::apiResource("meals", MenuController::class)->only(['index', 'show']);
    
    Route::group(['middleware'=> ['auth:sanctum']], function(){
        Route::apiResource("orders", OrderController::class)->only(['index', 'show']);
        
        Route::get('auth/user', [LoginController::class, 'info']);
        Route::post('auth/logout', [LoginController::class, 'logout']);

    });
});
