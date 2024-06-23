<?php

namespace Database\Seeders;

use App\Models\Fish;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $fishNames = [
            'Angel Fish',
            'Clownfish',
            'Goldfish',
            'Swordtail',
            'Molly',
            'Guppy',
            'Platty',
            'Tiger Barb',
            'Discus Barb',
            'Betta Fish',
            'Koi Fish',
            'Flowerhorn Fish',
            'Pacu Fish',
            'Neon Tetra',
            'Zebra Fish',
            'Catfish',
            'Arrowana',
        ];

        $fish = count($fishNames);

        for ($fish; $fish > 0; $fish-- )
        {
            DB::table('fish')->insert([
                'name' => fake()->randomElement($fishNames),
                'price' => fake()->numberBetween(50, 1000),
                'description' => fake()->paragraph(3, false),
                'stock' => fake()->numberBetween(100, 500),
            ]);
        }

    }
}
