<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null, // This will be set later
            'house_num' => fake()->randomNumber(4, false),
            'street_name' => fake()->streetName(),
            'town' => fake()->city(),
            'city' => fake()->city(),
            'zip_code' => fake()->randomNumber(4, true),
        ];
    }
}
