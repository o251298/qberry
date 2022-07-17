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
    //весь список доступных локаций с информацией о количестве свободных блоков в каждой
    Route::get('locations', [LocationController::class, 'index'])->middleware('auth:sanctum');
    //форма, где пользователь может ввести объем продуктов (в м3), необходимую температуру хранения и сроки хранения (диапазон дат, не дольше 24 дней).
    Route::post('booking-blocks-by-location', [LocationController::class, 'create'])->middleware('auth:sanctum');
    //при согласии пользователя с результатами калькулятора, он нажимает на кнопку бронирования “Book blocks”
    Route::post('confirm-booking', [LocationController::class, 'store'])->middleware('auth:sanctum');
    //отображаются все брони пользователя за все время с актуальными статусами и затратами
    Route::get('my-bookings', [BookingController::class, 'index'])->middleware('auth:sanctum');
    //отображается бронь пользователя по указанному id
    Route::get('booking/{id}', [BookingController::class, 'show'])->middleware('auth:sanctum');
});
