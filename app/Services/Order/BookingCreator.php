<?php

namespace App\Services\Order;

use App\Models\Block;
use App\Models\Booking;
use App\Services\NoSQL\NoSQLStoreInterface;
use App\Services\NoSQL\RedisStore;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingCreator
{
    public $objects;
    public $time;
    public NoSQLStoreInterface $store;

    public function __construct(protected string $hash)
    {
        $this->store = new RedisStore();
        if (!$this->store->exists($this->hash)) throw new \Exception("This key is not found!");
        $this->objects = unserialize($this->store->get($this->hash));
        $this->time    = (array)json_decode($this->store->get($this->hash . "&time"));
    }

    public function save(): Booking
    {
        return OrderStore::saveBooking(auth()->user(), $this->hash, Str::random(12), 1, Booking::CalculateCost(Block::PAYMENT_PER_DAY, count($this->objects), (Carbon::create($this->time['start'])->diff(Carbon::create($this->time['end']))->d)), $this->time['end']);
    }
}
