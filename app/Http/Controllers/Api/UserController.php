<?php

namespace App\Http\Controllers\Api;

use App\Helper\CookieHelper;
use App\Helper\SessionStorageHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Jobs\SendMailJob;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
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





    public function register(RegisterRequest $request): JsonResponse
    {

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registration successful.',
        ], Response::HTTP_CREATED);
    }




    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a response with the user and token
        return response()->json([
            'access_token' => $token,
            'success' => true,
            'message' => 'User login successful.'
        ], Response::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        // Revoke the user's current token
        auth()->user()->tokens()->delete();
        // auth()->user()->currentAccessToken()->delete();
        // Return a response indicating the user has been logged out
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ], Response::HTTP_NO_CONTENT);
    }



    public function forgetPassword(Request $request): JsonResponse
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Retrieve the email from the request
        $UserEmail = $request->input('email');

        // Generate a random OTP
        $OTP = rand(100000, 999999);
        $details = ['code' => $OTP];

        // Send the OTP email
        dispatch(new SendMailJob($UserEmail, $details));
        // Mail::to( $UserEmail)->send(new OTPMail($details));

        // Find the user by email and update the OTP
        $isUser = User::where('email', $UserEmail)->firstOrFail();
        $isUser->update(['otp' => $OTP]);

        // Create a cookie instance
        $cookie = cookie('email', $UserEmail, 60 * 24 * 30); // 30 days

        // Return the response with the cookie
        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully.'
        ])->withCookie($cookie);
    }

    public function emailVerify(Request $request)
    {
        $request->validate([
            'otp' => 'required|min:6'
        ]);
        $otp = $request->input('otp');
        $email = CookieHelper::getCookieByName('email');
        $isUser = User::where(['email' => $email, 'otp' => $otp])->first();

        if ($isUser) {
            $isUser->update(['otp' => 0]);
            return response()->json([
                'success' => true,
                'message' => 'email verify successful.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email'
            ]);
        }

    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8'
        ]);


        $password = $request->input('password');
        $email = CookieHelper::getCookieByName('email');
        $isUser = User::where(['email' => $email])->firstOrFail();

        if ($isUser) {
            $isUser->update([
                'password' => $password
            ]);
            $cookie = cookie('email', '', -1);
            return response()->json([
                'success' => true,
                'message' => 'Password reset successful.'
            ])->withCookie($cookie);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user'
            ]);
        }


    }





}
