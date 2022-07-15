<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlockBooking>
 */
class BlockBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'booking_id' => $this->faker->numberBetween(1,20),
            'block_id' => $this->faker->numberBetween(1, 100),
            'start' => '2022-11-0' . $this->faker->numberBetween(1,10),
            'end' => '2022-11-' . $this->faker->numberBetween(11,30),
        ];
    }
}
