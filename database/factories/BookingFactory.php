<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'              => $this->faker->numberBetween(1, 10),
            'status'               => $this->faker->numberBetween(0, 1),
            'hash'                 => Hash::make(Str::random(16)),
            'password_for_booking' => Str::random(12),
            'date_payment'         => $this->faker->dateTime()
        ];
    }
}
