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
Route::post('/auth/login', [AuthController::class, 'loginUser'])->name('login');

Route::middleware('auth:sanctum')->group(function (){
    Route::get('locations', [LocationController::class, 'index']); // api docs
    Route::post('location/get-blocks-by-location', [LocationController::class, 'create']); //
    Route::post('location/booking-store', [LocationController::class, 'store']);
    Route::get('bookings', [BookingController::class, 'index']);
});
