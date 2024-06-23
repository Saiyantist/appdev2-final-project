<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Bowl;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
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
            'ship_by_date' => Carbon::now()->addDays(fake()->numberBetween(2, 7))->format('F j, Y'),
            'status' => fake()->randomElement(['placed', 'canceled', 'completed']),
        ];
    }
}
