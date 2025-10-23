<div class="d-flex min-vh-100 justify-content-center align-items-center" 
     style="background: url('/assets/media/background/login-background.png') no-repeat center center; background-size: cover;">

    <div class="card shadow" style="max-width: 550px; width: 100%;">
        <div class="card-body">
            <h2 class="text-center my-5">Forgot Password</h2>
            <span class="d-block text-center fw-semibold mb-10">
                Hosba Durian Farm Sdn Bhd Administrative Portal
            </span>

            <h5 class="d-block text-center fw-semibold mb-10">
                Reset your password using OTP verification
            </h5>

            {{-- Success message --}}
            @if ($message)
                <div class="alert alert-success text-center mb-4">{{ $message }}</div>
            @endif

            {{-- Phone input + Send OTP --}}
            <div class="mb-4">
                <x-input-label for="phone" :value="__('Phone Number')" />
                <div class="input-group my-3">
                    <input type="tel" id="phone" class="form-control" wire:model.defer="phone" placeholder="Enter phone number"
                        @if($otpSent) disabled @endif required>
                    <button class="btn btn-light" type="button" wire:click="sendOtp" 
                        @if($otpSent) disabled @endif>
                        {{ $otpSent ? 'OTP Sent' : 'Send OTP' }}
                    </button>
                </div>
                <x-input-error :messages="$errors->get('phone')" class="text-danger mt-1" />
            </div>

            {{-- OTP input (only show after sendOtp) --}}
            @if ($otpSent)
                <div class="mb-4">
                    <x-input-label for="otp" :value="__('OTP')" />
                    <x-input-text id="otp" class="form-control my-3" type="text" wire:model.defer="otp"
                        placeholder="Enter OTP" required />
                    <x-input-error :messages="$errors->get('otp')" class="text-danger mt-1" />
                </div>

                <div class="text-center mt-8">
                    <x-primary-button class="btn btn-primary px-8 py-3" wire:click="verifyOtp">
                        {{ __('Verify OTP') }}
                    </x-primary-button>
                </div>
            @endif
        </div>
    </div>
</div>
