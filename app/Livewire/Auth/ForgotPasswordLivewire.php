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
    public $countdown = 0;

    protected $rules = [
        'phone' => 'required|exists:users,phone',
    ];

    protected $listeners = [
        'setCountdown',
    ];

    /**
     * Send OTP to user's phone using ForgotPasswordService
     */
    public function sendOtp(ForgotPasswordService $service)
    {
        $this->phone = $this->formatPhone($this->phone);

        $this->validateOnly('phone');

        try {
            // Send OTP via CallMeBot or other integrated service
            $service->sendOtp($this->phone);

            // Update component state
            $this->otpSent = true;
            $this->message = 'OTP has been sent to your WhatsApp.';
            $this->countdown = 20;
            $this->dispatch('otpSent');
        } catch (\Exception $e) {
            $this->addError('phone', $e->getMessage());
        }
    }

    public function setCountdown($value)
    {
        // keep server-side countdown in sync with JS
        $this->countdown = (int) $value;
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

    private function formatPhone($phone)
    {
        $cleaned = preg_replace('/\D/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            $cleaned = '6' . substr($cleaned, 1); // 0123456789 → 60123456789
        } elseif (!str_starts_with($cleaned, '6')) {
            $cleaned = '60' . $cleaned; // 123456789 → 60123456789
        }

        return $cleaned;
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-livewire');
    }
}
