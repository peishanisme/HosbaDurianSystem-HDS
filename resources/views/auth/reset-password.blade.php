<x-layouts.guest>
    <div class="d-flex min-vh-100 justify-content-center align-items-center"
        style="background: url('/assets/media/background/login-background.png') no-repeat center center; background-size: cover;">
        <div class="card shadow" style="max-width: 550px; width: 100%;">
            <div class="card-body">
                <h1 class="text-center my-5">Reset Password</h1>
                <span class="d-block text-center fw-semibold mb-10">
                    Hosba Durian Farm Sdn Bhd Administrative Portal
                </span>

                @if (session('status'))
                    <div class="alert alert-success text-center">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('password.reset') }}">
                    @csrf

                    <div class="mb-5">
                        <x-input-label for="new_password" :value="__('New Password')" />
                        <x-input-text id="new_password" class="form-control my-3"
                            type="password" name="new_password" required placeholder="Enter new password" />
                        <x-input-error :messages="$errors->get('new_password')" class="text-danger mt-1" />
                    </div>

                     <div class="mb-5">
                        <x-input-label for="new_password_confirmation" :value="__('Confirm Password')" />
                        <x-input-text id="new_password_confirmation" class="form-control my-3"
                            type="password" name="new_password_confirmation" required placeholder="Confirm new password" />
                        <x-input-error :messages="$errors->get('new_password_confirmation')" class="text-danger mt-1" />
                    </div>

                    <div class="text-center mt-10">
                        <x-primary-button class="btn btn-primary px-8 py-3">
                            {{ __('Submit') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.guest>
