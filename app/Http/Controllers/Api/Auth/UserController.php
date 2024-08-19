<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function getAuthUserWithToken(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            return response()->json([
                'user' => $user,
                'success' => true,
                'token' => $request->bearerToken()
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([

                'success' => false,
                'message' => $th->getMessage(),
                'status code' => $th->getCode()
            ], Response::HTTP_BAD_REQUEST);
        }

    }





    // get all users
    public function index(Request $request): JsonResponse
    {
        try {
            $users = User::get();
            return response()->json([
                'message' => 'Get all users',
                'success' => true,
                'users' => UserResource::collection($users)
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
                'status code' => $th->getCode()
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function show(User $user)
    {
        try {
            return response()->json([
                'message' => 'Get user',
                'success' => true,
                'user' => UserResource::make($user)
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
                'status code' => $th->getCode()
            ], Response::HTTP_BAD_REQUEST);
        }
    }









    // store user 
    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->validated());
            return response()->json([
                'message' => 'User created successfully.',
                'success' => true,
                'user' => UserResource::make($user)
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
                'status code' => $th->getCode()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // user update
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        try {
            $user->update($request->validated());
            return response()->json([
                'message' => 'User updated successfully.',
                'success' => true,
                // 'users' => UserResource::make($user)
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
                'status code' => $th->getCode()
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    // user delete 
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            return response()->json([
                'message' => 'User deleted successfully.',
                'success' => true,
                'users' => null
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
                'status code' => $th->getCode()
            ], Response::HTTP_BAD_REQUEST);
        }
    }




}
