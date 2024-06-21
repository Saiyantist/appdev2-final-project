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
        ]);

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
            'token' => $user->createToken('tomsworld' . $user->name)->plainTextToken,
        ])

        // if (!$user || !Hash::check($request->password, $user->password)) {
        //     throw ValidationException::withMessages([
        //         'email' => ['The provided credentials are incorrect.'],
        //     ]);
        // }

        // $token = $user->createToken('auth-token')->plainTextToken;

        // return response()->json(['token' => $token], 200);
    }

    public function showRegister()
    {
        return view('auth.register');
    }   

    public function showLogin()
    {
        return view('auth.login');
    }   

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
