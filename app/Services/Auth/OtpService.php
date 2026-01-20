<?php

namespace App\Services\Auth;

use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function send(User $user): void
    {
        EmailOtp::where('user_id', $user->id)->delete();

        $otp = random_int(100000, 999999);

        EmailOtp::create([
            'user_id' => $user->id,
            'otp' => Hash::make($otp),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::raw(
            "Your email verification code is {$otp}. It is valid for 10 minutes.",
            fn ($m) => $m->to($user->email)->subject('Email Verification Code')
        );
    }

    public function verify(User $user, string $otp): bool
    {
        $record = EmailOtp::where('user_id', $user->id)->first();

        if (!$record) {
            return false;
        }

        if (now()->greaterThan($record->expires_at)) {
            $record->delete();
            return false;
        }

        if (!Hash::check($otp, $record->otp)) {
            return false;
        }

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $record->delete();

        return true;
    }
}
