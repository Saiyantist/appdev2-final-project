<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFishbackRequest;
use App\Models\Fishback;
use App\Models\User;
use App\Models\Bowl;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class FishbackController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fishbacks = Fishback::with('fish')->get();

        return $this->success($fishbacks, 'ALL Fishbacks', 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function myFishbacks()
    {
        $fishbacks = User::where('id', Auth::user()->id)->with('fishbacks')->get();

        return $this->success($fishbacks, 'ALL Fishbacks', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFishbackRequest $request)
    {
        $validated = $request->validated();

        $userOrders = Order::where('user_id', Auth::user()->id)->with('user')->get();
        // $userOrders = Order::where('user_id', 1)->with('user')->get();

        foreach($userOrders as $userOrder)
        {
            if($userOrder->id == $validated['order_id'])
            {
                // May proceed only if ORDER is "COMPLETED"
                if ($userOrder->status == "completed")
                {
                    $bowl = Bowl::where('order_id', $userOrder->id)->with('bowlItems')->first();
    
                    foreach($bowl->bowlItems as $bowlItem)
                    {
                        // May proceed if inputted fish matches with any fish they actually ORDERED.
                        if($bowlItem->fish_id == $validated['fish_id'])
                        {
                            Fishback::create([
                                'fish_id' => $validated['fish_id'],
                                'order_id' => $validated['order_id'],
                                'user_id' => Auth::user()->id,
                                'rating' => $validated['rating'],
                                'review' => $validated['review'],
                            ]);

                            return $this->success(Fishback::latest('created_at')->first(), 'Fishback successfully Created!', 201);
                        }
                    }
                    // Fish not found in order, returns their order's items
                    return $this->error($bowl->bowlItems, 'Fish doesn\'t exist in your Order, try another fish_id', 404);
                }            
            }
        
            elseif($userOrder->id != $validated['order_id'])
            {
                // Order ID is wrong
                return $this->error($userOrders, 'Order ID is wrong', 404);
            }
        }
        
        // No Orders available for feedback, returns user's orders
        return $this->error($userOrders, 'No Orders available for fishback', 404);

    }

    /**
     * Display the specified resource.
     */
    public function show(Fishback $fishback)
    {
        return $this->success($fishback, 'A Fishback', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        return $this->error(null, 'Updating is not available for fishbacks', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fishback $fishback)
    {
        $checkFishback = Fishback::where('user_id', Auth::user()->id)->pluck('id')->toArray();

        if(in_array($fishback->id, $checkFishback))
        {
            $fishback->delete();
            return $this->success(Fishback::find($fishback->id) ? 'meron pa eh' : null, 'Fishback successfully Deleted!', 204);
        }
        
        elseif(!in_array($fishback->id, $checkFishback))
        {
            $fishback = Fishback::where('user_id', Auth::user()->id)->get();
            return $this->error($fishback, 'You can\'t delete what is not yours. Here are your fishbacks.', 403);
        }
    }
}
