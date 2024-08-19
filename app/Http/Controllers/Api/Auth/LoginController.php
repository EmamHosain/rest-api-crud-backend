<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;


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


    public function logout(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Revoke the token that was used to authenticate the current request
        $user->tokens()->delete();

        // Return a response indicating the user has been logged out
        return response()->json([
            'message' => 'User successfully logged out',
        ], 200);
    }
}
