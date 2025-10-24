<x-layouts.guest>
    <div class="d-flex min-vh-100 justify-content-center align-items-center" style="background: url('/assets/media/background/login-background.png') no-repeat center center; background-size: cover;">
        <div class="card shadow" style="max-width: 550px; width: 100%;">
            <div class="card-body">
                <!-- Sign In Title -->
                <h2 class="text-center my-5">Verify OTP</h2>
                <span class="d-block text-center fw-semibold mb-10">
                    Hosba Durian Farm Sdn Bhd Administrative Portal
                </span>
                <h5 class="d-block text-center fw-semibold mb-10">
                    Enter your phone number to reset your password
                </h5>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('forgot.password.sendOtp') }}">
                    @csrf

                    <!-- Phone -->
                    <div class="mb-4">
                        <x-input-label for="phone" :value="__('Phone Number')" />
                        <x-input-text id="phone" class="form-control my-3" type="tel" name="phone"
                            placeholder="phone number" :value="old('phone')" required autofocus autocomplete="tel" />
                        <x-input-error :messages="$errors->get('phone')" class="text-danger mt-1" />
                    </div>

                    <div class="text-center mt-5">
                        <x-primary-button class="btn btn-primary px-8 py-3 my-5">
                            {{ __('Submit') }}
                        </x-primary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-layouts.guest>
