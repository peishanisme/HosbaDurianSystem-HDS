<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ForgotPasswordService
{
    public function sendOtp($phone)
    {
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            throw new Exception('User not found.');
        }

        $otp = rand(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['phone' => $phone],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'updated_at' => now(),
            ]
        );

        $this->sendWhatsAppOtp($phone, $otp);

        return $otp;
    }

    protected function sendWhatsAppOtp($phone, $otp)
    {
        // format phone as international (e.g. Malaysia: 60123456789)
        $message = urlencode("Your Hosba Durian Farm OTP is: {$otp}. It will expire in 5 minutes.");

        $url = "https://api.callmebot.com/whatsapp.php?phone=60126002335&text={$message}&apikey=8199824";

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new Exception('Failed to send OTP. Please try again later.');
        }
    }

    public function verifyOtp($phone, $otp)
    {
        $record = DB::table('password_resets')
            ->where('phone', $phone)
            ->where('otp', $otp)
            ->first();

        if (!$record) {
            return false;
        }

        if (Carbon::parse($record->expires_at)->isPast()) {
            // OTP expired — clean up
            DB::table('password_resets')->where('phone', $phone)->delete();
            return false;
        }

        // Valid OTP — remove it to prevent reuse
        DB::table('password_resets')->where('phone', $phone)->delete();
        return true;
    }

    public function resetPassword($phone, $newPassword)
    {
        $user = User::where('phone', $phone)->firstOrFail();
        $user->password = Hash::make($newPassword);
        $user->save();
        
        return $user;
    }
}
