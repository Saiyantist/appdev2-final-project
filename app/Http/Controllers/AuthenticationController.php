<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    use HttpResponses;

    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Token of ' . $user->name)->plainTextToken,
        ], 'User Registration Success!', 201);

        // return response()->json([
        //     'message' => 'You have successfully registered!'
        //     ],201);
    }

    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        if(!Auth::attempt($request->only('email', 'password')))
        {
            return $this->error(null, 'Credentials do not match in the database.', 401);
        }

        $user = User::where('email', $validated['email'])->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Token of ' . $user->name)->plainTextToken,
        ], 'Logged in Successfully!');
    }

    public function logout(Request $request)
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success(null, 'Logged out Successfully.');
    }
    
    public function showRegister()
    {
        return view('auth.register');
    }   

    public function showLogin()
    {
        return view('auth.login');
    }   


}
