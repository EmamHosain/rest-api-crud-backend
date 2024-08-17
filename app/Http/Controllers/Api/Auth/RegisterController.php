<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{


    public function register(RegisterRequest $request)
    {
        // Validate the incoming request using the rules in RegisterRequest
        $validatedData = $request->validated();

        // Create a new user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'is_admin' => $validatedData['is_admin'] ?? 2
        ]);

        // Generate an API token for the authenticated user
        $token = $user->createToken('token')->plainTextToken;

        // Return a response with the created user and access token
        return response()->json([
            'message' => 'User successfully registered and logged in',
            'token' => $token,
        ], 201);
    }




}
