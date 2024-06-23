<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use App\Models\Bowl;
use App\Models\BowlItem;
use App\Models\Fish;
use Database\Factories\BowlItemFactory;

// use App\Models\


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FishSeeder::class);
        User::factory(100)->create()->each(function ($user){
            $user->address()->save(Address::factory()->make());
            
            $orders = Order::factory()->count(rand(1,2))->make();
            $user->orders()->saveMany($orders);

            foreach ($orders as $order) {
                $bowl = Bowl::factory()->make([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                ]);

                $order->bowl()->save($bowl);

                $totalAmount = 0;

                // Get fishes to be randomly bought
                $fishes = Fish::inRandomOrder()->limit(rand(1, 5))->get();

                // For each chosen fish, put it into a bowl_item
                foreach ($fishes as $fish){
                    $quantity = rand(1, 5);
                    $subtotal = $fish->price * $quantity;

                    $bowlItem = BowlItem::factory()->make([
                        'fish_id' => $fish->id,
                        'bowl_id' => $bowl->id,
                        'quantity' => $quantity,
                        'sub_total' => $subtotal,
                    ]);
                    
                    $bowl->bowlItems()->save($bowlItem);

                    // Update total amount of the bowl
                    $totalAmount += $subtotal;

                }
                
                // Complete the last process for creating a Bowl
                $bowl->total_amount = $totalAmount;
                $bowl->save();
            }

        });



        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
