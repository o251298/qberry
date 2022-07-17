<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\BlockBooking;

use App\Models\Booking;
use App\Models\Location;
use App\Services\Timezone\TimezoneCreator;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // обновить статусы блоков
    public function updateBlockStatus()
    {
        $carbon = Carbon::now();
        // status active
        $blockBookingActive = BlockBooking::query()
            ->where('start', '<=' , $carbon->toDateString())
            ->where('end', '>=', $carbon->toDateString())->get();
        // status passive
        $blockBookingPassive = BlockBooking::query()
            ->where('start', '>' , $carbon->toDateString())
            ->where('end', '>', $carbon->toDateString())->get();
        foreach ($blockBookingPassive as $passive)
        {
            $obj = $passive->getBlock()->first();
            $obj->status = Block::FREE_BLOCK;
            $obj->save();
        }
        foreach ($blockBookingActive as $active)
        {
            $obj = $active->getBlock()->first();
            $obj->status = 1;
            $obj->save();
        }
        dd(1);
    }

    public function sendBalance()
    {
        $carbon = Carbon::now();
        $m = $carbon->month;
        $y = $carbon->year;
        //$user = auth()->user()->id;
        $payments = Booking::query()->whereBetween('date_payment', ["{$y}-{$m}-01", "{$y}-{$m}-31"])->get();
        //$payments = Booking::query()->where('user_id', $user)->whereBetween('date_payment', ["{$y}-{$m}-01", "{$y}-{$m}-31"])->get();
        $sum = 0;
        foreach ($payments as $payment)
        {
            $sum += $payment->amount;
        }
        dump($payments);
        dd($sum);
    }


    public function testTime()
    {
        $block = Block::find(5);
        dd();
    }
}
