<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\FishController;
use App\Http\Controllers\BowlItemController;
use App\Http\Controllers\BowlController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FishbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 *  GUEST Roues
 */
Route::controller(AuthenticationController::class)->group(function (){
    Route::get('/login', 'showLogin')->name('login.screen');
    Route::post('/login', 'login')->name('login');
    
    Route::get('/register', 'showRegister')->name('register.screen');
    Route::post('/register', 'register')->name('register');
});

/**
 *  SANCTUM-PROTECTED Routes
 */
Route::group(['middleware'=> ['auth:sanctum']], function (){
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    Route::apiResources([
        'address' => AddressController::class,
        'bowl_items' => BowlItemController::class,
        'bowls' => BowlController::class,
        'fishbacks' => FishbackController::class,
        'fishes' => FishController::class,
        'orders' => OrderController::class,
    ]);
});