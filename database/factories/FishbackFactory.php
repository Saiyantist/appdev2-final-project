<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fishback>
 */
class FishbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fish_id' => null, // This will be set later
            'order_id' => null, // This will be set later
            'rating' => fake()->numberBetween(1, 5), // Random rating between 1 and 5
            'review' => fake()->paragraph(), // Random review text
        ];
    }
}
