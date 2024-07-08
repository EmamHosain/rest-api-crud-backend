<?php

namespace App\Http\Controllers\Api;

use App\Helper\CookieHelper;
use App\Helper\SessionStorageHelper;
use App\Jobs\SendMailJob;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{


    public function index(Request $request)
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'success' => true,
            'access_token' => $request->bearerToken(),
        ]);
    }





    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create a new token for the user
        // $token = $user->createToken('auth_token')->plainTextToken;

        // Return a response with the user and token
        return response()->json([
            // 'access_token' => $token,
            'success' => true,
            'message' => 'User registration successful.',
        ], 201);
    }




    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Find the user by email
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
        ]);
    }

    // public function auth(AuthUserRequest $re)


    public function logout(Request $request)
    {
        // Revoke the user's current token
        auth()->user()->tokens()->delete();
        // auth()->user()->currentAccessToken()->delete();
        // Return a response indicating the user has been logged out
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }



    public function forgetPassword(Request $request)
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
