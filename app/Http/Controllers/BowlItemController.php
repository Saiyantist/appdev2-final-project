<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBowlItemRequest;
use App\Http\Requests\UpdateBowlItemRequest;
use App\Models\Bowl;
use App\Models\BowlItem;
use App\Models\Fish;
use App\Models\Order;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class BowlItemController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bowlId = Bowl::where('user_id', Auth::user()->id)->pluck('id');

        $bowlItems = BowlItem::whereIn('bowl_id', $bowlId)->get()->groupBy('bowl_id')->toArray();

        if(empty($bowlItems))
        {
            return $this->success(null, 'You have no Bowl Items', 200);
        }

        else if (!empty($bowlItems))
        {
            return $this->success($bowlItems, 'All YOUR Bowl Items.', 200);
        }
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
        if($fish->stock >= $validated['quantity'])
        {
            $hasBowl = Bowl::with('bowlItems')->where('user_id', $userId)->first();
            $hasOrder = Order::where('user_id', $userId)->first();

            // Check if has EXISTING Bowl but not yet CHECKED-OUT (Orded Placed).
            if($hasBowl && !$hasOrder)
            {
                $bowlItems = $hasBowl->bowlItems->toArray();

                // Check if NO EXISTING Bowl Items
                if(empty($bowlItems))
                {
                    // Update the Fish's stock in the Database..
                    // DAPAT SA OREDRCONTROLLER PA PALA

                    // Fish::where('id', $validated['fish_id'])
                    // ->update([
                    //     'stock' => $fish->stock - $validated['quantity'],
                    // ]);

                    $bowlItem = BowlItem::create([
                        'fish_id' => $validated['fish_id'],
                        'bowl_id' => $hasBowl->id,
                        'quantity' => $validated['quantity'],
                        'sub_total' => $validated['quantity'] * $fish->price,
                    ]);
            
                    return $this->success($bowlItem, 'Bowl Item succssfully Added to Bowl', 201);
                }

                elseif(!empty($bowlItems))
                {

                    // Check if Bowl Items is more than 1,
                    // To FURTHER check for duplicate Bowl Items with same fish_id.
                    if(count($bowlItems) > 1)
                    {
                        foreach($bowlItems as $bowlItem){
                           $sameFish[] = $bowlItem['fish_id']; 
                        }

                        // Check if Bowl Item's fish_id is the SAME as the desired fish_id .
                        if(in_array($fish->id, $sameFish))
                        {
                            return $this->error(null, 'Use UPDATE endpoint to add/subtract quantity of your Fish.', 400);
                        }
       
                        // Create a new Bowl Item and insert the new Bowl Item
                        elseif(!in_array($fish->id, $sameFish))
                        {
        
                            // Update the Fish's stock in the Database..
                            // DAPAT SA OREDRCONTROLLER PA PALA
        
                            // Fish::where('id', $validated['fish_id'])
                            // ->update([
                            //     'stock' => $fish->stock - $validated['quantity'],
                            // ]);
        
                            $bowlItem = BowlItem::create([
                                'fish_id' => $validated['fish_id'],
                                'bowl_id' => $hasBowl->id,
                                'quantity' => $validated['quantity'],
                                'sub_total' => $validated['quantity'] * $fish->price,
                            ]);
                    
                            return $this->success($bowlItem, 'Bowl Item succssfully Added to Bowl', 201);
                        }
                    }
                    
                    elseif(count($bowlItems) == 1)
                    {
                        // Check if Bowl Item's fish_id is the SAME as the desired fish_id .    
                        if($fish->id == $bowlItems[0]['fish_id']){
                            return $this->error(null, 'Use UPDATE endpoint to add/subtract quantity of your Fish.', 400);
                        }

                        // Create a new Bowl Item and insert the new Bowl Item
                        elseif($fish->id != $bowlItems[0]['fish_id'])
                        {
        
                            // Update the Fish's stock in the Database..
                            // DAPAT SA OREDRCONTROLLER PA PALA
        
                            // Fish::where('id', $validated['fish_id'])
                            // ->update([
                            //     'stock' => $fish->stock - $validated['quantity'],
                            // ]);
        
                            $bowlItem = BowlItem::create([
                                'fish_id' => $validated['fish_id'],
                                'bowl_id' => $hasBowl->id,
                                'quantity' => $validated['quantity'],
                                'sub_total' => $validated['quantity'] * $fish->price,
                            ]);
                    
                            return $this->success($bowlItem, 'Bowl Item succssfully Added to Bowl', 201);
                        }

                    }

                }
            }

            // If no Bowls yet, CREATE a new Bowl then a Bowl Item
            elseif(is_null($hasBowl))
            {
                $bowl = Bowl::create([
                    'user_id' => $userId,
                    'total_amount' => $validated['quantity'] * $fish->price,
                ]);
                
                // Update the Fish's stock in the Database..
                // DAPAT SA OREDRCONTROLLER PA PALA
                
                // Fish::where('id', $validated['fish_id'])
                // ->update([
                //     'stock' => $fish->stock - $validated['quantity'],
                // ]);
        
                $bowlItem = BowlItem::create([
                    'fish_id' => $validated['fish_id'],
                    'bowl_id' => $bowl->id,
                    'quantity' => $validated['quantity'],
                    'sub_total' => $validated['quantity'] * $fish->price,
                ]);
        
                return $this->success($bowlItem, 'Bowl Item succssfully Added to new Bowl', 201);
            }
    
        }

        elseif ($fish->stock < $validated['quantity'])
        {   
            $data = ['Fish\'s stock' => $fish->stock, 'Your desired quantity to buy' => $validated['quantity']];
            return $this->error($data, 'Insufficient Fish stock. Please lower your quantity find any other fish we have.', 409);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BowlItem $bowlItem)
    {
        $userId = Auth::user()->id;

        $bowlId = Bowl::where('user_id', $userId)->pluck('id');

        $bowlItems =  BowlItem::whereIn('bowl_id', $bowlId)->pluck('id')->toArray();

        if (in_array($bowlItem->id, $bowlItems))
        {
            return $this->success(BowlItem::where('id', $bowlItem->id)->get(), 'Your Bowl Item.', 200);
        }

        elseif (!in_array($bowlItem->id, $bowlItems))
        {
            return $this->error(null, 'You can\'t see what is not yours.', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBowlItemRequest $request, BowlItem $bowlItem)
    {
        $validated = $request->validated();

        $userId = Auth::user()->id;
        $bowlId = Bowl::where('user_id', $userId)->pluck('id');
        $bowlItems =  BowlItem::whereIn('bowl_id', $bowlId)->pluck('id')->toArray();

        if (in_array($bowlItem->id, $bowlItems))
        {
            $fish = Fish::where('id', $bowlItem->fish_id)->first();

            // Check if sufficient stocks
            if($fish->stock >= $validated['quantity'])
            {
                // dd('Stock is greater, Update me');
                BowlItem::where('id', $bowlItem->id)
                ->update([
                    'quantity' => $validated['quantity'],
                    'sub_total' => $fish->price * $validated['quantity'],
                ]);

                $bowlItem = BowlItem::where('id', $bowlItem->id)->first();
                return $this->success($bowlItem, 'Bowl Item details successfully Updated!', 200);
            }
    
            elseif ($fish->stock < $validated['quantity'])
            {   
                $data = ['Fish\'s stock' => $fish->stock, 'Your desired quantity to buy' => $validated['quantity']];
                return $this->error($data, 'Insufficient Fish stock. Please lower your quantity find any other fish we have.', 409);
            }
            

            // return $this->success($fish, 'Your Bowl Item.', 200);

            return $this->success(BowlItem::where('id', $bowlItem->id)->get(), 'Your Bowl Item.', 200);


    
        }

        elseif (!in_array($bowlItem->id, $bowlItems))
        {
            return $this->error(null, 'You can\'t change what is not yours.', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BowlItem $bowlItem)
    {
        $userId = Auth::user()->id;
        $bowlId = Bowl::where('user_id', $userId)->pluck('id');
        $bowlItems =  BowlItem::whereIn('bowl_id', $bowlId)->pluck('id')->toArray();

        if (in_array($bowlItem->id, $bowlItems))
        {
            $data = ['Before:' => $bowlItem];

            $bowlItem->delete();

            $data['After'] = $bowlItem;
            
            return $this->error($data, 'Bowl Item successfully Deleted!', 204);
        }

        elseif (!in_array($bowlItem->id, $bowlItems))
        {
            return $this->error(null, 'You can\'t delete what is not yours.', 403);
        }
    }
}
