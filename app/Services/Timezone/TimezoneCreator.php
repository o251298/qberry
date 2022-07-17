<?php

namespace App\Services\Timezone;

use App\Models\Location;
use Illuminate\Support\Carbon;

class TimezoneCreator
{
    public static function createServerDate(string $timezone, string $date) : string
    {
        /*
         * Если клиент находится в -4 зоне, у него на часах 12:00
         * то у нас на сервере +4
         *
         * Если клиент находится в +4 и у него 12:00
         * то у нас на сервере -4
         *
         */
        if ($timezone < 0) {
            $timezone_user = -1 * $timezone;
            $carbon_server = Carbon::create($date)->addHour($timezone_user);
            return $carbon_server->format('Y-m-d H:i:s');
        }
        if ($timezone > 0) {
            $carbon_server = Carbon::create($date)->subHour($timezone);
            return $carbon_server->format('Y-m-d H:i:s');
        } else {
            $carbon_server = Carbon::create($date);
            return $carbon_server->format('Y-m-d H:i:s');
        }
    }

    public static function createUserDate(string $timezone, string $date) : string
    {
        if ($timezone < 0) {
            $timezone_user = -1 * $timezone;
            $carbon_server = Carbon::create($date)->subHour($timezone_user);
            return $carbon_server->format('Y-m-d H:i:s');
        }
        if ($timezone > 0) {
            $carbon_server = Carbon::create($date)->addHour($timezone);
            return $carbon_server->format('Y-m-d H:i:s');
        } else {
            $carbon_server = Carbon::create($date);
            return $carbon_server->format('Y-m-d H:i:s');
        }
    }
}
