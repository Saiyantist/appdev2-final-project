<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// GUEST Roues
Route::controller(AuthenticationController::class)->group(function (){
    Route::get('/login', 'showLogin')->name('login.screen');
    Route::post('/login', 'login')->name('login');
    
    Route::get('/register', 'showRegister')->name('register.screen');
    Route::post('/register', 'register')->name('register');
});

