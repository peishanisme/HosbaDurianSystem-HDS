<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use App\Services\ForgotPasswordService;
use App\Actions\FormatPhoneNumberAction;
use App\Services\Auth\PasswordResetService;

class ForgotPasswordLivewire extends Component
{
    public $phoneNum;
    public $phone;
    public $otp;
    public $otpSent = false;
    public $message;
    public $countdown = 0;

    protected $rules = [
        'phone' => 'required',
    ];

    protected $listeners = [
        'setCountdown',
    ];

    private function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email);

        if (strlen($name) <= 2) {
            return $name[0] . 'x@' . $domain;
        }

        return
            substr($name, 0, 1) .
            str_repeat('x', strlen($name) - 2) .
            substr($name, -1) .
            '@' . $domain;
    }

    /**
     * Send OTP to user's phone using ForgotPasswordService
     */
    public function sendOtp(ForgotPasswordService $service)
    {
        // keep raw input in phoneNum and write formatted value to a separate property
        $this->phone = FormatPhoneNumberAction::handle($this->phoneNum);

        // validate the formatted phone field only
        $this->validateOnly('phone');

        $user = User::where('phone', $this->phone)->first();

        if (!$user) {
            $this->addError('phone', 'No user found with this phone number. Please contact your manager for assistance.');
            return;
        }

        if (!$user->email) {
            $this->addError('phone', 'This phone number exists but no email is registered. Please contact your manager.');
            return;
        }

        try {
            $service->sendOtp($this->phone);
            $maskedEmail = $this->maskEmail($user->email);
            // Update component state
            $this->otpSent = true;
            $this->message = 'OTP has been sent to your registered email: ' . $maskedEmail;
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

    public function render()
    {
        return view('livewire.auth.forgot-password-livewire');
    }
}
