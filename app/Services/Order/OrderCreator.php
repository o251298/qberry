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

    public function save($objs, $objsTime): void
    {
        static $timezone = null;
        foreach ($objs as $item) {
            if ($timezone === null) {
                $timezone = $item->getLocation()->first()['timezone'];
            }
            OrderStore::saveBlockBooking($this->booking, $item, $timezone, $objsTime);
        }
    }
}
