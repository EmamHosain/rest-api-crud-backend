<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function login(LoginRequest $loginRequest)
    {
        // Validate the incoming request using the rules in LoginRequest
        $credentials = $loginRequest->validated();

        // Attempt to log in the user
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            // Authentication was successful
            $user = Auth::user();

            // Generate an API token for the authenticated user
            $token = $user->createToken($user->name)->plainTextToken;

            // Return a response with the authenticated user and access token
            return response()->json([
                'message' => 'User successfully logged in',
                'token' => $token,
            ], 200);
        }

        // Authentication failed
        return response()->json([
            'message' => 'Invalid email or password',
        ], 401);
    }
}
