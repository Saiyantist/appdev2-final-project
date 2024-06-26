<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Bowl;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)->get();

        
        if(count($orders) == 0)
        {
            return $this->success(null, 'You have no Orders. Go Scoop up sommeee fish!', 200);
        }
        
        else if (count($orders) > 0)
        {
            foreach($orders as $order){
                $bowl = Bowl::where('order_id', $order->id)->with('bowlItems')->first();
                
                $data[] = [
                    'Order ID' => $order->id,
                    'Order Status' => $order->status,
                    'Bowl' => $bowl,
                ];
            }
            return $this->success($data, 'Your Order and its Bowl Items', 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $bowlToOrder = Bowl::where('user_id', Auth::user()->id)->where('order_id', null)->get();

        if(count($bowlToOrder) == 0)
        {
            return $this->success(null, 'You have no bowl. Go scoop up some of our fishes!', 200);
        }
        
        elseif(count($bowlToOrder) == 1)
        {

            Order::create([
                'user_id' => Auth::user()->id,
                'status' => 'placed',
            ]);

            $orderId = Order::latest('id')->first()->id;
            
            $bowlToOrder->first()->order_id = 199;

            $bowlId = $bowlToOrder->first()->id;

            Bowl::find($bowlId)->update([
                'order_id' => $orderId,
            ]);

            $data = [
                'Order' => Order::latest('id')->first(),
                'Bowl' => Bowl::latest('updated_at')->first(),
            ];

            return $this->success($data, 'Order successfully Placed!', 201);
        }
        
        else
        {
            return $this->error(null, 'Seems like we both don\'t know this', 400);
        }

        // $orders = Order::where('user_id', Auth::user()->id)->get();

        // dd('User Bowls', $userBowls, 'Orders', $orders);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $orderId = Order::where('user_id', Auth::user()->id)->pluck('id')->toArray();

        if (in_array($order->id, $orderId))
        {
            return $this->success(Order::find($order->id), 'Your Order.', 200);
        }

        elseif (!in_array($order->id, $orderId))
        {
            return $this->error(null, 'You can\'t see what is not yours.', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Order $order)
    {
        $checkOrder = Order::where('user_id', Auth::user()->id)->pluck('id')->toArray();

        if(in_array($order->id, $checkOrder))
        {
            $order->update([
                'ship_by_date' => Carbon::now()->addDays(fake()->numberBetween(2, 7))->format('F j, Y'),
            ]);

            if($order->status == 'placed')
            {
                $order->update(['status' => 'completed']);
            }
            elseif($order->status == 'completed')
            {
                $order->update(['status' => 'canceled']);
            }
            elseif($order->status == 'canceled')
            {
                $order->update(['status' => 'placed']);
            }
            
            return $this->success(Order::latest('updated_at')->first(), 'Order successfully Updated!', 201);
        }

        elseif(!in_array($order->id, $checkOrder))
        {
            return $this->error(null, 'You can\'t change what is not yours.', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $checkOrder = Order::where('user_id', Auth::user()->id)->pluck('id')->toArray();

        if(in_array($order->id, $checkOrder))
        {
            $order->delete();
            return $this->success(Order::find($order->id) ? 'meron pa eh' : null, 'Order successfully Deleted!', 204);
        }

        elseif(!in_array($order->id, $checkOrder))
        {
            return $this->error(null, 'You can\'t delete what is not yours.', 403);
        }
    }
}
