<div>
    <div class="d-flex min-vh-100 justify-content-center align-items-center"
        style="background: url('/assets/media/background/login-background.png') no-repeat center center; background-size: cover;">

        <div class="card shadow" style="max-width: 550px; width: 100%;">
            <div class="card-body">
                <h1 class="text-center my-5">Forgot Password</h1>

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
                <div class="mb-5">
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <div class="input-group my-3">
                        <span class="input-group-text bg-light border-end-0">+60</span>
                        <input type="tel" id="phone" class="form-control" wire:model.defer="phoneNum"
                            placeholder="Enter phone number" {{ $countdown > 0 ? 'disabled' : '' }} required>

                        <button id="sendOtpBtn" class="btn btn-primary" type="button" wire:click="sendOtp"
                            {{ $countdown > 0 ? 'disabled' : '' }}>
                            @if ($countdown > 0)
                                Resend in {{ $countdown }}s
                            @elseif ($otpSent)
                                Resend OTP
                            @else
                                Send OTP
                            @endif
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="text-danger mt-1" />

                </div>

                {{-- OTP input --}}
                @if ($otpSent)
                    <div class="mb-5">
                        <x-input-label for="otp" :value="__('OTP')" />
                        <input id="otp" class="form-control my-3" type="text" wire:model.defer="otp"
                            placeholder="Enter OTP" {{ $countdown > 0 ? '' : '' }} required />
                        <x-input-error :messages="$errors->get('otp')" class="text-danger mt-1" />
                    </div>

                    <div class="text-center mt-8">
                        <x-primary-button class="btn btn-primary px-8 py-3" wire:click="verifyOtp">
                            {{ __('Verify OTP') }}
                        </x-primary-button>
                    </div>
                @endif
            </div>
            <div class="text-center py-4 bg-light border-top small text-muted">
                &copy; {{ date('Y') }} Hosba Durian Farm Sdn Bhd. All rights reserved.
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Run when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            // Start JS timer when Livewire dispatches 'otpSent'
            Livewire.on('otpSent', () => {
                // grab elements inside DOM each time (handles Livewire re-renders)
                const getBtn = () => document.getElementById('sendOtpBtn');
                const getPhone = () => document.getElementById('phone');

                let countdown = 20;

                // ensure UI disabled immediately (works until server-side re-render)
                const btn = getBtn();
                const phoneInput = getPhone();
                if (btn) btn.disabled = true;
                if (phoneInput) phoneInput.disabled = true;

                // send initial value to Livewire server so blade disables when it re-renders
                Livewire.dispatch('setCountdown', {
                    value: countdown
                });

                const timer = setInterval(() => {
                    // re-query elements (they might be replaced by Livewire)
                    const btnNow = getBtn();
                    const phoneNow = getPhone();

                    if (countdown > 0) {
                        if (btnNow) btnNow.innerText = `Resend in ${countdown}s`;
                        // update server-side property so blade will render disabled attribute
                        Livewire.dispatch('setCountdown', {
                            value: countdown
                        });

                        countdown--;
                    } else {
                        clearInterval(timer);
                        // final update to server (0)
                        Livewire.dispatch('setCountdown', {
                            value: 0
                        });


                        // update UI one last time
                        if (btnNow) {
                            btnNow.innerText = 'Resend OTP';
                            btnNow.disabled = false;
                        }
                        if (phoneNow) phoneNow.disabled = false;
                    }
                }, 1000);
            });
        });
    </script>
@endpush
