<x-layouts.guest>
    <div class="d-flex min-vh-100 justify-content-center align-items-center" style="background: url('/assets/media/background/login-background.png') no-repeat center center; background-size: cover;">
        <div class="card shadow" style="max-width: 550px; width: 100%;">
            <div class="card-body">
                <!-- Sign In Title -->
                <h1 class="text-center my-5">Sign In</h1>
                <span class="d-block text-center fw-semibold mb-10">
                    Hosba Durian Farm Sdn Bhd Administrative Portal
                </span>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Phone -->
                    <div class="mb-5">
                        <x-input-label for="phone" :value="__('Phone Number')" />
                        <x-input-text id="phone" class="form-control my-3" type="tel" name="phone"
                            placeholder="Phone number" :value="old('phone')" required autofocus autocomplete="tel" />
                        <x-input-error :messages="$errors->get('phone')" class="text-danger mt-1" />
                    </div> 

                    <!-- Password -->
                    <div class="mb-5">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-input-text id="password" class="form-control my-3" type="password" name="password" required
                            placeholder="Password" autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="text-danger mt-1" />
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <!-- Remember Me -->
                        <div class="mb-4 form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="form-check-label">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        @if (Route::has('forgot.password'))
                            <a class="text-decoration-none small text-muted" href="{{ route('forgot.password') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                    </div>

                    <div class="text-center my-5">
                        <x-primary-button class="btn btn-primary px-8 py-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-layouts.guest>
