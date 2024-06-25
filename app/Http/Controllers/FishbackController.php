<?php

namespace App\Http\Controllers;

use App\Models\Fishback;
use App\Models\User;
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Fishback $fishback)
    {
        return $this->error(null, 'Bad Request', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fishback $fishback)
    {
        
    }
}
