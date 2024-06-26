<?php

namespace App\Http\Controllers;

use App\Models\Bowl;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;

class BowlController extends Controller
{
    use HttpResponses;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $userId = Auth::user()->id;
        $bowls = Bowl::where('user_id', $userId)->with('bowlItems')->get();

        // Check if many Bowls
        // To further check if the bowl collected is ok to be shown
        if(count($bowls) > 1)
        {   
            for ($i = 0; $i < count($bowls); $i++)
            {
                $order_status = Order::where('id', $bowls[$i]->order_id )->pluck('status')->implode(',');
                $order_id = Order::where('id', $bowls[$i]->order_id )->pluck('id')->implode(',');
                $orders[] = [
                    'bowl_id' => $bowls[$i]->id,
                    'order_id' => $order_id,
                    'order_status' => $order_status,
                ];
            }
                        
            foreach($orders as $order)
            {
                $status = $order['order_status'];

                // Check if Bowl does not belong to an Order yet
                // Displays the Bowl if TRUE
                if($status == ""){

                    $bowl = Bowl::where('id',$order['bowl_id'])->with('bowlItems')->get();

                    $bowlItems = $bowl->first()->bowlItems;

                    foreach($bowlItems as $bowlItem)
                    {
                        $totalAmount[] = $bowlItem->sub_total;
                    }
                    
                    $bowl->first()->update([
                        'total_amount' => array_sum($totalAmount),
                    ]);
        
                    $bowl = Bowl::find($order['bowl_id']);
                    return $this->success($bowl, 'Your Bowl.', 200);
                }
            }

            // If all collected bowls is already checked out, inform user of empty Bowl.
            return $this->success(null, 'Your Bowl is empty. Go Scoop up sommeee fish!', 200);
        }

        // This means user Has No Orders yet. (a Window Shopper haha)
        elseif(count($bowls) == 1)
        {
            $bowlItems = $bowls->first()->bowlItems;

            foreach($bowlItems as $bowlItem)
            {
                $totalAmount[] = $bowlItem->sub_total;
            }

            $bowls->first()->update([
                'total_amount' => array_sum($totalAmount),
            ]);

            $bowl = Bowl::find($bowls->first()->id);
            return $this->success($bowls, 'Your Bowl.', 200);
        }

        // This means user is a FIRST-TIME User
        elseif(count($bowls) == 0)
        {
            return $this->success(null, 'Add a fish now to get a bowl!', 200);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->error(null, 'Bad Request', 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bowl $bowl)
    {
        return $this->error(null, 'Bad Request', 400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bowl $bowl)
    {
        return $this->error(null, 'Bad Request', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bowl $bowl)
    {
        $userId = Auth::user()->id;
        $bowlId = Bowl::where('user_id', $userId)->pluck('id');

        $bowls =  Bowl::whereIn('id', $bowlId)->pluck('id')->toArray();

        if (in_array($bowl->id, $bowls))
        {
            $data = ['Before:' => $bowl];
            
            $bowl->delete();

            $data['After'] = Bowl::find($bowl->id);
            
            return $this->success($data, 'Bowl Item successfully Deleted!', 200);
        }

        elseif (!in_array($bowl->id, $bowls))
        {
            return $this->error(null, 'You can\'t delete what is not yours.', 403);
        }

        else
        {
            return $this->error(null, 'Bad Request.', 400);
        }
    }
}
