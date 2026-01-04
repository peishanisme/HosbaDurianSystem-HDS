<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetOtpMail;

class ForgotPasswordService
{
    public function sendOtp(string $phone): void
    {
        $user = User::where('phone', $phone)->first();

        // Do NOT reveal user existence
        if (!$user || !$user->email) {
            return;
        }

        $otp = random_int(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['phone' => $phone],
            [
                'otp'   => Hash::make($otp),
                'expires_at' => Carbon::now()->addMinutes(5),
                'updated_at' => now(),
            ]
        );

        $this->sendEmailOtp($user->email, $otp);
    }

    protected function sendEmailOtp(string $email, int $otp): void
    {
        Mail::to($email)->send(
            new PasswordResetOtpMail($otp)
        );
    }

    public function verifyOtp(string $phone, string $otp): bool
    {
        $record = DB::table('password_resets')
            ->where('phone', $phone)
            ->first();

        if (!$record) {
            return false;
        }

        if (Carbon::parse($record->expires_at)->isPast()) {
            DB::table('password_resets')->where('phone', $phone)->delete();
            return false;
        }

        if (!Hash::check($otp, $record->otp)) {
            return false;
        }

        DB::table('password_resets')->where('phone', $phone)->delete();
        return true;
    }

    public function resetPassword(string $phone, string $newPassword): void
    {
        $user = User::where('phone', $phone)->firstOrFail();

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
