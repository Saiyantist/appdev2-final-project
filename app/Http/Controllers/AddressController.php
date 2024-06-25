<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Address $address)
    {
        $address = Address::where('user_id',  Auth::user()->id)->first();

        if(!is_null($address))
        {
            return $this->success($address, 'This is your address.', 200);
        }
        elseif(is_null($address))
        {
            return $this->error(null, 'No address found.', 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        $address = Address::where('user_id',  Auth::user()->id)->first();

        // dd($address);
        // dd(!is_null($address));
        if(!is_null($address))
        {
            return $this->error($address, 'You already have an address!', 400);
        }

        elseif(is_null($address))
        {
            $validated = $request->validated();
    
            $address = Address::create([
                'user_id' => Auth::user()->id,
                'house_num' => $validated['house_num'],
                'street_name' => $validated['street_name'],
                'town' => $validated['town'],
                'city' => $validated['city'],
                'zip_code' => $validated['zip_code'],
            ]);
    
            return $this->success($address, 'Address Stored!', 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        if(Auth::user()->id === $address->user_id){
            return $this->success($address, 'Your Address.', 200);
        }
        return $this->error(null, 'This is not your address.', 403);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {       
        // IT FINALLY WORKED AFTER 2 HOURS BEING STUCK
        // https://stackoverflow.com/questions/50691938/patch-and-put-request-does-not-working-with-form-data

        $currentAddressId = Address::where('user_id',  Auth::user()->id)->pluck('id')->first();

        if($address->id === $currentAddressId)
        {
            $validated = $request->validated();
            Address::where('id', $currentAddressId)
            ->update([
                "house_num" => $validated['house_num'],
                "street_name" => $validated['street_name'],
                "town" => $validated['town'],
                "city" => $validated['city'],
                "zip_code" => $validated['zip_code'],
            ]);
            $address = Address::where('id', $currentAddressId)->first();
            return $this->success($address, 'Address successfully Updated!!', 201);
        }
        else
        {
            return $this->error(null, 'You can\'t change what is not yours.', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $addressId = Address::find($address)->pluck('id')->first();
        $checkAddress = Address::where('user_id', Auth::user()->id)->pluck('id')->first();

        // dd($address->delete());
        if ($addressId === $checkAddress)
        {
            $address->delete();
            return $this->success(null, 'Address successfully Deleted!', 200);
        }
        else
        {
            return $this->error(null, 'This is not your address.', 403);
        }

        // $deleteAddress = Address::where('user_id', Auth::user()->id)->get();

        // $deleteAddress = Address::where('user_id', Auth::user()->id)->get();

        // dd($deleteAddress);
    }
}