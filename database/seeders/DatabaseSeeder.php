<?php

namespace Database\Seeders;

use App\Models\BlockBooking;
use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Fridge;
use App\Models\Block;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // create Location data
//        DB::table('locations')->insert([
//            'location' => 'Wilmington',
//            'timezone' => 'GMT-4',
//        ]);
//        DB::table('locations')->insert([
//            'location' => 'Portland',
//            'timezone' => 'GMT-7',
//        ]);
//        DB::table('locations')->insert([
//            'location' => 'Toronto',
//            'timezone' => 'GMT-4',
//        ]);
//        DB::table('locations')->insert([
//            'location' => 'Warsaw',
//            'timezone' => 'GMT+2',
//        ]);
//        DB::table('locations')->insert([
//            'location' => 'Valencia',
//            'timezone' => 'GMT+2',
//        ]);
//        DB::table('locations')->insert([
//            'location' => 'Shanghai',
//            'timezone' => 'GMT+8',
//        ]);
        // create Fridge data
        //Fridge::factory(50)->create();
        // create Block data
        //Block::factory(150)->create();
        //Booking::factory(20)->create();
        BlockBooking::factory(40)->create();
    }
}
