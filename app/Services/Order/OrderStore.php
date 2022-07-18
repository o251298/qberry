<?php

namespace App\Services\Order;


use App\Models\Block;
use App\Models\BlockBooking;
use App\Models\Booking;
use App\Services\Timezone\TimezoneCreator;
use Carbon\Carbon;
use App\Models\User;


class OrderStore
{
    public static function saveBooking(User $user, string $hash, string $password_for_booking, int $status, float $amount, string $date_payment): Booking
    {
        $booking = Booking::create([
            'user_id'              => $user->id,
            'hash'                 => $hash,
            'password_for_booking' => $password_for_booking,
            'status'               => $status,
            'amount'               => $amount,
            'date_payment'         => Carbon::create($date_payment)
        ]);
        return $booking;
    }

    public static function saveBlockBooking(Booking $booking, Block $block, int $timezone, array $objsTime): void
    {
        BlockBooking::create([
            'booking_id' => $booking->id,
            'block_id'   => $block->id,
            'start'      => TimezoneCreator::createServerDate($timezone, $objsTime['start']),
            'end'        => TimezoneCreator::createServerDate($timezone, $objsTime['end']),
        ]);
    }
}
