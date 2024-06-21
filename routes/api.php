<?php

use App\Http\Controllers\AuthenticationController;
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

    
});