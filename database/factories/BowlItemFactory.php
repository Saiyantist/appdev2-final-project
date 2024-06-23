<?php

namespace Database\Factories;

use App\Models\Fish;
use App\Models\Bowl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BowlItem>
 */
class BowlItemFactory extends Factory
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
            'bowl_id' => null, // This will be set later
            'quantity' => 0,  // This will be set later
            'sub_total' => 0, // This will be calculated later
        ];
    }
}
