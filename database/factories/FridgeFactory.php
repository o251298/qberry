<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fridge>
 */
class FridgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'location_id' => $this->faker->numberBetween(1, 6),
            'temperature' => $this->faker->numberBetween(-20, -1),
            // generate unique name
            'name'        => Hash::make((string) microtime())
        ];
    }
}
