<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    public function token(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);

        $user = User::where('email', $data['email'])->first();

        if ($user) {
            // for signed url
            // $token = Password::createToken($user);

            $code = rand(100000, 999999);

            DB::table('password_reset_tokens')->updateOrInsert([
                'email' => $user->email,
            ], [
                'token'      => Hash::make($code),
                'created_at' => now(),
            ]);

            Mail::to($user->email)->send(new PasswordReset($user->name, $code));
        }

        return response()->json(['message' => 'A password reset OTP has been sent to your email'], 201);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'token', 'password', 'password_confirmation'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::RESET_THROTTLED) {
            return response()->json(['message' => 'Too many attempts. Please try again later.'], 429);
        }

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'], 200)
            : response()->json(['message' => 'Invalid or expired reset link. Please request a new one.'], 422);
    }
}
