<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view for the given token.
     */
    public function showResetForm(Request $request, $token = null)
    {
        // Check if email is present
        if (!$request->has('email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Invalid password reset link. Email is missing.']);
        }

        // Check token existence and expiry
        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return redirect()->route('password.request')->withErrors(['email' => 'This password reset link is invalid or has expired.']);
        }

        // Check Expiry
        $tokenCreatedAt = \Carbon\Carbon::parse($record->created_at);
        $expiresInMinutes = config('auth.passwords.users.expire', 60);

        if ($tokenCreatedAt->addMinutes($expiresInMinutes)->isPast()) {
            return redirect()->route('password.request')->withErrors(['email' => 'This password reset link has expired.']);
        }

        // Check Token match (Laravel stores tokens as hashes)
        if (!Hash::check($token, $record->token)) {
            return redirect()->route('password.request')->withErrors(['email' => 'This password reset link is invalid.']);
        }

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
