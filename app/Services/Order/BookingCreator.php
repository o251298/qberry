<?php

namespace App\Services\Order;

use App\Models\Block;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class BookingCreator
{
    public $objects;
    public $time;
    public function __construct(protected string $hash)
    {
        $this->objects     = unserialize(Redis::get($this->hash));
        $this->time = (array)json_decode(Redis::get($this->hash . "&time"));
    }

    public function save() : Booking
    {
        $booking  = Booking::create([
            'user_id' => auth()->user()->id,
            'hash'    => $this->hash,
            'status'  => 1,
            'amount'  => Block::PAYMENT_PER_DAY * count($this->objects) * (Carbon::create($this->time['start'])->diff(Carbon::create($this->time['end']))->d),
            'date_payment' => Carbon::create($this->time['end'])
        ]);
        return $booking;
    }
}
