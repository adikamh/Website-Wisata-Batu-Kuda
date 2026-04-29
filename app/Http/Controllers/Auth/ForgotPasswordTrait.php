<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

trait ForgotPasswordTrait
{
    private function issueResetOtp(User $user): void
    {
        $otp = (string) random_int(100000, 999999);
        $otpExpiredAt = now()->addMinutes(5);

        $user->forceFill([
            'otp' => $otp,
            'otp_expired_at' => $otpExpiredAt,
        ])->save();

        Mail::raw(
            "Kode OTP reset password Anda adalah {$otp}. Kode ini berlaku selama 5 menit sampai {$otpExpiredAt->format('H:i')}.\n\nGunakan kode ini untuk mengatur ulang password Anda.",
            function ($message) use ($user): void {
                $message->to($user->email)->subject('Kode OTP Reset Password');
            }
        );
    }
}
