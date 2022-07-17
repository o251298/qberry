<?php

namespace App\Http\Controllers;

use App\Models\BlockBooking;
use App\Models\Booking;
use App\Models\User;


class AdminController extends Controller
{

    public function index()
    {
        return view('admin');
    }


    public function updateBlockStatus()
    {
        // обновить статусы блоков
        BlockBooking::updateStatus();
        return redirect()->back();
    }

    public function showUsers()
    {
        return view('users', ['users' => User::all()]);
    }

    public function sendBalance(User $user)
    {
        // подсчитать цену за текущий месяц, для каждого пользователя
        $payments = Booking::createInvoice($user);
        return view('balance', ['payments' => $payments->get(), 'sumInvoice' => $payments->sum('amount')]);
    }

}
