<?php

namespace App\Services\Order;

use App\Models\BlockBooking;
use App\Models\Booking;
use App\Services\Timezone\TimezoneCreator;

class OrderCreator
{

    public function __construct(protected Booking $booking)
    {
    }

    public function save($objs, $objsTime) : void
    {
        static $timezone = null;
        foreach ($objs as $item) {
            if ($timezone === null)
            {
                $timezone = $item->getLocation()->first()['timezone'];
            }
            $newProduct             = new BlockBooking();
            $newProduct->booking_id = $this->booking->id;
            $newProduct->block_id   = $item->id;
            $newProduct->start      = TimezoneCreator::createServerDate($timezone, $objsTime['start']);
            $newProduct->end        = TimezoneCreator::createServerDate($timezone, $objsTime['end']);
            $newProduct->save();
        }
    }
}
