<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFishRequest;
use App\Http\Requests\UpdateFishRequest;
use App\Models\Fish;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class FishController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(Fish::with('fishbacks')->get(), 'Scoop your fish now!', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFishRequest $request)
    {
        $validated = $request->validated();

        // dump($validated);

        $fish = Fish::create([
            'user_id' => Auth::user()->id,
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'stock' => $validated['stock'],
        ]);

        return $this->success($fish, 'Fish successfully Listed!', 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Fish $fish)
    {
        $showFish = Fish::with('fishbacks')->where('id', $fish->id)->get();
        return $this->success($showFish, 'A fish!', 201);;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFishRequest $request, Fish $fish)
    {
        $validated = $request->validated();
        Fish::where('id', $fish->id)
        ->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'stock' => $validated['stock'],
        ]);
        $fish = Fish::where('id', $fish->id)->first();
        return $this->success($fish, 'Fish details successfully Updated!', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fish $fish)
    {
        $fish->delete();
        return $this->success(null, 'Fish listing successfully Removed');
    }
}
