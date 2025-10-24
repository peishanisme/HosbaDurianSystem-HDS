<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\ForgotPasswordService;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    protected $forgotPasswordService;

    public function __construct(ForgotPasswordService $service)
    {
        $this->forgotPasswordService = $service;
    }

    public function showPhoneForm()
    {
        return view('auth.forgot-password');
    }

    // Step 1: Send OTP
    // public function sendOtp(Request $request)
    // {
    //     $request->validate([
    //         'phone' => 'required|exists:users,phone',
    //     ]);

    //     $otp = rand(100000, 999999);

    //     DB::table('password_resets')->updateOrInsert(
    //         ['phone' => $request->phone],
    //         [
    //             'otp' => $otp,
    //             'expires_at' => Carbon::now()->addMinutes(5),
    //             'updated_at' => now(),
    //         ]
    //     );

    //     // 💡 Free option: show OTP directly (for dev)
    //     file_get_contents("https://api.callmebot.com/whatsapp.php?phone=60126002335&text=Your+OTP+is+$otp&apikey=8199824");

    //     return redirect()->route('forgot.password.verifyForm')->with('otp', $otp);
    // }

    // Step 2: Show verify OTP form
    public function showVerifyForm()
    {
        if (!session('otp_phone')) {
            return redirect()->route('forgot.password');
        }
        return view('auth.verify-otp');
    }

    // Step 2: Verify OTP
    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'otp' => 'required|numeric',
    //     ]);

    //     $phone = session('otp_phone');

    //     $record = DB::table('password_resets')
    //         ->where('phone', $phone)
    //         ->where('otp', $request->otp)
    //         ->first();

    //     if (!$record) {
    //         return back()->withErrors(['otp' => 'Invalid OTP']);
    //     }

    //     if (Carbon::parse($record->expires_at)->isPast()) {
    //         return back()->withErrors(['otp' => 'OTP expired']);
    //     }

    //     session(['reset_phone' => $phone]);
    //     return redirect()->route('password.reset.form');
    // }

    public function showResetForm()
    {
        if (!session('reset_phone')) {
            return redirect()->route('forgot.password')->withErrors('Session expired, please request OTP again.');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        $phone = session('reset_phone');

        $this->forgotPasswordService->resetPassword($phone, $request->new_password);

        Session::forget('reset_phone');

        return redirect()->route('login')->with('status', 'Password reset successful! You may now log in.');
    }
}
