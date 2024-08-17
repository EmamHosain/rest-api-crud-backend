<?php

namespace App\Http\Controllers\Api\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'success' => true,
            'token' => $request->bearerToken()
        ], Response::HTTP_OK);
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
