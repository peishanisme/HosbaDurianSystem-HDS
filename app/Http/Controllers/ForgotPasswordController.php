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

    // Step 2: Show verify OTP form
    public function showVerifyForm()
    {
        if (!session('otp_phone')) {
            return redirect()->route('forgot.password');
        }
        return view('auth.verify-otp');
    }

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
