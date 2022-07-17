<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\BookingController;

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
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::prefix('v1')->group(function () {
    Route::get('locations', [LocationController::class, 'index'])->middleware('auth:sanctum');
    Route::post('booking-blocks-by-location', [LocationController::class, 'create'])->middleware('auth:sanctum');
    Route::post('confirm-booking', [LocationController::class, 'store'])->middleware('auth:sanctum');
    Route::get('my-bookings', [BookingController::class, 'index'])->middleware('auth:sanctum');
});
