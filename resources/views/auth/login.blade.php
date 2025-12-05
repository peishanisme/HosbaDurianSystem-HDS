<x-layouts.guest>
    <div class="min-vh-100 d-flex justify-content-center align-items-center bg-cover bg-center"
        style="background: url('/assets/media/background/login-background.png') no-repeat center center; background-size: cover;">

        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="max-width: 480px; width: 100%;">
            <div class="card-body p-10">

                <!-- Logo (optional) -->
                <div class="text-center mb-4">
                    <img alt="Logo" src="{{ app(\App\Services\MediaService::class)->get('logo/system-logo-v2.png') }}"
                        class="app-sidebar-logo-default" style="height: 150px;" />
                </div>

                <!-- Title -->
                <div class="mb-8">
                    <h2 class="text-center fw-bold mb-2 text-dark">Welcome Back</h2>
                    <p class="text-center text-muted mb-5">
                        Sign in to your Hosba Durian Farm Admin Portal
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Phone -->
                    <div class="mb-4">
                        <x-input-label for="phone" :value="__('Phone Number')" />
                        <div class="input-group mt-2">
                            <span class="input-group-text bg-light border-end-0">+60</span>
                            <input id="phone" class="form-control border-start-0" type="tel" name="phone"
                                placeholder="1xxxxxxxxx" value="{{ old('phone') }}" required autofocus
                                autocomplete="tel"  />
                        </div>
                        {{-- <x-input-error :messages="$errors->get('phone')" class="text-danger mt-1" /> --}}
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <div class="input-group mt-2">
                            <input id="password" type="password" name="password" class="form-control"
                                placeholder="Enter your password" required autocomplete="current-password" />
                            <span class="input-group-text bg-light border-start-0" id="togglePassword"
                                style="cursor:pointer;">
                                <i class="bi bi-eye-slash "></i>
                            </span>
                        </div>
                        {{-- <x-input-error :messages="$errors->get('password')" class="text-danger mt-1" /> --}}
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="d-flex justify-content-between align-items-center my-5">
                        <div class="form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="form-check-label text-muted small">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        @if (Route::has('forgot.password'))
                            <a class="text-decoration-none small fw-semibold text-primary"
                                href="{{ route('forgot.password') }}">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mt-5">
                        <x-primary-button class="btn btn-primary px-8 py-3 ">
                            {{ __('Sign In') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="text-center py-4 bg-light border-top small text-muted">
                &copy; {{ date('Y') }} Hosba Durian Farm Sdn Bhd. All rights reserved.
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Toggle password visibility
            document.getElementById('togglePassword').addEventListener('click', function() {
                const password = document.getElementById('password');
                const icon = this.querySelector('i');
                if (password.type === 'password') {
                    password.type = 'text';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                } else {
                    password.type = 'password';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                }
            });
        </script>
    @endpush
</x-layouts.guest>
