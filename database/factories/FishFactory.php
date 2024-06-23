<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fish>
 */
class FishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    // public $fishNames = [
    //     'Angel Fish',
    //     'Clownish',
    //     'Goldfish',
    //     'Swordtail',
    //     'Molly',
    //     'Guppy',
    //     'Platty',
    //     'Tiger Barb',
    //     'Discus Barb',
    //     'Betta Fish',
    //     'Koi Fish',
    //     'Flowerhorn Fish',
    //     'Pacu Fish',
    //     'Neon Tetra',
    //     'Zebra Fish',
    //     'Catfish',
    //     'Arrowana',
    // ];

    public function definition(): array
    {

        return [
            // 'name' => fake()->randomElement($this->fishNames),
            // 'price' => fake()->numberBetween(50, 1000),
            // 'description' => fake()->paragraph(3, false),
            // 'stock' => fake()->numberBetween(0, 100),
        ];
    }
}
