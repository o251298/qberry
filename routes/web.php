<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Форма для бронирования холодильного помещание
Route::get('/', [ClientController::class, 'index'])->name('main');
// Вывод информации о бронировании
Route::post('client/get-block', [ClientController::class, 'getBlocks'])->name('client_get_block');
// Подтверждение бронирования
Route::post('client/confirm-booking', [ClientController::class, 'confirmBooking'])->name('client_confirm_booking');

// Админские роуты
Route::prefix('admin')->group(function () {
    // Обновить статусы всем блокам
    Route::get('/update-block-status', [AdminController::class, 'updateBlockStatus'])->name('update_status_block');
    // Отобразить баланс клиента
    Route::get('/send-balance/{user}', [AdminController::class, 'sendBalance'])->name('send_balance');
    // Отобразить список клиентов
    Route::get('/show-users', [AdminController::class, 'showUsers'])->name('admin');
});

