<?php

namespace App\Http\Controllers\Api\Auth;

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
                // token save to database 
                DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                $if_save_data_to_database = DB::table('password_reset_tokens')->insert([
                    'email' => $user->email,
                    'token' => $token
                ]);
                if ($if_save_data_to_database) {
                    $custom_link = env('FRONTEND_URL') . "/api/reset-password/{$token}?email={$user->email}";
                    $user->notify(new PasswordResetNotification($custom_link));
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
    public function resetPassword(ResetPasswordRequest $request)
    {

        DB::beginTransaction();
        try {
            $email = $request->email;
            $token = $request->token;
            $password = $request->password;

            // Find the token in the password_reset_tokens table
            $user_token = DB::table('password_reset_tokens')->where([
                'email' => $email,
                'token' => $token
            ])->first();



            // If the token exists, update the password
            if ($user_token) {
                User::where('email', $email)->update([
                    'password' => Hash::make($password),
                ]);

                // Delete the token from the password_reset_tokens table
                DB::table('password_reset_tokens')->where([
                    'email' => $email,
                    'token' => $token
                ])->delete();

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Password update successful.'
                ], Response::HTTP_OK);
            }

            // If the token is not found, return an error
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Invalid token or email.'
            ], Response::HTTP_BAD_REQUEST);



        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], $th->getCode());
        }

    }

}
