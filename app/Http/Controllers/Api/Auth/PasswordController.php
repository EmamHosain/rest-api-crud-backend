<?php

namespace App\Http\Controllers\Api\Auth;

use App\Jobs\PasswordResetJob;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\TokenGenerator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\PasswordResetNotification;

class PasswordController extends Controller
{

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        DB::beginTransaction();
        try {
            $email = $request->email;
            $user = User::where('email', $email)->first();

            if ($user) {
                $token = TokenGenerator::generateToken(64);

                // delete oldest token for this email
                DB::table('password_reset_tokens')->where('email', $user->email)->delete();


                // new token save to database for this email
                $if_save_data_to_database = DB::table('password_reset_tokens')->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => now(), // Add this line to set the current timestamp
                ]);


                if ($if_save_data_to_database) {
                    $custom_link = env('FRONTEND_URL') . "/api/reset-password/{$token}?email={$user->email}";

                    // dispatch password reset job
                    PasswordResetJob::dispatch($user, $custom_link)->onQueue('high');
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Email sent for reset password.'
                ], 200);

            } else {

                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Email must be save to database.'
                ], 404);

            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 404);
        }
    }



    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed',
        ]);

        // Find the password reset record
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Invalid token or email'], 200);
        }

        // Check if the token is expired (1 minute)
        $tokenCreatedAt = \Carbon\Carbon::parse($passwordReset->created_at);
        $isExpired = $tokenCreatedAt->addMinutes(1)->isPast();

        if ($isExpired) {
            return response()->json(['message' => 'The password reset link has expired'], 400);
        }

        // Reset password
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Optionally, delete the reset record
            DB::table('password_resets')->where('email', $request->email)->delete();

            return response()->json(['message' => 'Password successfully reset']);
        }

        return response()->json(['message' => 'User not found'], 404);
    }


}
