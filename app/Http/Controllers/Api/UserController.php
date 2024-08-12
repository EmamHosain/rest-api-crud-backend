<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'success' => true,
            'access_token' => $request->bearerToken(),
        ], Response::HTTP_OK);
    }

}
