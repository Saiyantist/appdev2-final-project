<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBowlItemRequest;
use App\Models\Bowl;
use App\Models\BowlItem;
use App\Models\Fish;
use App\Models\Order;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BowlItemController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(BowlItem::all()->groupBy('bowl_id'), 'All Bowl Items.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBowlItemRequest $request)
    {
        $validated = $request->validated();
        $userId = Auth::user()->id;
        $fish = Fish::where('id', $validated['fish_id'])->first();

        // Check if sufficient stocks
        if(!($fish->stock <= $validated['quantity']))
        {
            $hasBowl = Bowl::with('bowlItems')->where('user_id', $userId)->first();
            $hasOrder = Order::where('user_id', $userId)->first();

            $bowlItems = $hasBowl->bowlItems;
            foreach($bowlItems as $bowlItem){
               $sameFish[] = $bowlItem->fish_id; 
            }
            
            // Check if has EXISTING Bowl but not yet CHECKED-OUT (Orded Placed).
            if($hasBowl && !$hasOrder)
            {
   
                // Check if Bowl Item's fish_id is the SAME as the desired fish_id .
                if(in_array($fish->id, $sameFish))
                {
                    return $this->error(null, 'Use UPDATE endpoint to add/subtract quantity of your Fish.', 400);
                }

                // Create a new Bowl Item
                elseif(!in_array($fish->id, $sameFish))
                {
                    $bowlItem = BowlItem::create([
                        'fish_id' => $validated['fish_id'],
                        'bowl_id' => $hasBowl->id,
                        'quantity' => $validated['quantity'],
                        'sub_total' => $validated['quantity'] * $fish->price,
                    ]);
            
                    return $this->success($bowlItem, 'Bowl Item succssfully Added to Bowl', 201);
                }
            }

            // If no Bowls yet, CREATE a new Bowl then a Bowl Item
            elseif(!$hasBowl)
            {
                Bowl::create([
                    'user_id' => $userId,
                    'total_amount' => $validated['quantity'] * $fish->price,
                ]);
                
                $bowlItemId = Bowl::count();
        
                $bowlItem = BowlItem::create([
                    'fish_id' => $validated['fish_id'],
                    'bowl_id' => $bowlItemId,
                    'quantity' => $validated['quantity'],
                    'sub_total' => $validated['quantity'] * $fish->price,
                ]);
        
                return $this->success($bowlItem, 'Bowl Item succssfully Added to new Bowl', 201);
            }
    
        }


        
    }

    /**
     * Display the specified resource.
     */
    public function show(BowlItem $bowlItem)
    {
        return $this->success($bowlItem, 'A Bowl Item.', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BowlItem $bowlItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BowlItem $bowlItem)
    {
        //
    }
}
