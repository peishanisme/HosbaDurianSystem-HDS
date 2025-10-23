<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Services\Auth\PasswordResetService;
use App\Services\ForgotPasswordService;

class ForgotPasswordLivewire extends Component
{
    public $phone;
    public $otp;
    public $otpSent = false;
    public $message;

    protected $rules = [
        'phone' => 'required|exists:users,phone',
    ];

    /**
     * Send OTP to user's phone using ForgotPasswordService
     */
    public function sendOtp(ForgotPasswordService $service)
    {
        $this->validateOnly('phone');

        try {
            // Send OTP via CallMeBot or other integrated service
            $service->sendOtp($this->phone);

            // Update component state
            $this->otpSent = true;
            $this->message = 'OTP has been sent to your phone.';
        } catch (\Exception $e) {
            $this->addError('phone', $e->getMessage());
        }
    }

    /**
     * Verify the entered OTP
     */
    public function verifyOtp(ForgotPasswordService $service)
    {
        $this->validate([
            'otp' => 'required|numeric',
        ]);

        if ($service->verifyOtp($this->phone, $this->otp)) {
            session(['reset_phone' => $this->phone]);
            return redirect()->route('password.reset.form');
        } else {
            $this->addError('otp', 'Invalid or expired OTP.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-livewire');
    }
}
