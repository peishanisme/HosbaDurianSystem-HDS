<div class="container-fluid">
    {{-- profile details --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.user_profile') }}</h3>
        </div>
        <div class="card-body">

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label">{{ __('messages.name') }}</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="{{ __('messages.name') }}" wire:model="form.name">
                    <x-input-error :messages="$errors->get('form.name')" />
                </div>
            </div>

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label">{{ __('messages.email') }}</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" placeholder="{{ __('messages.email') }}" wire:model="form.email">
                    <x-input-error :messages="$errors->get('form.email')" />
                </div>
            </div>

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label">{{ __('messages.phone_number') }}</label>
                <div class="col-sm-9">
                    <div class="input-group mt-2">
                        <span class="input-group-text bg-light border-end-0">+60</span>
                        <input id="phone" class="form-control border-start-0" type="tel" name="phone"
                            placeholder="1xxxxxxxxx" wire:model="phone" required autofocus autocomplete="tel" />
                    </div>
                    <x-input-error :messages="$errors->get('form.phone')" />
                </div>
            </div>

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label">{{ __('messages.role') }}</label>
                <div class="col-sm-9">
                    <input type="role" class="form-control" placeholder="{{ __('messages.role') }}" wire:model="role" disabled>
                </div>
            </div>

        </div>
        <div class="card-footer text-end">
            <x-primary-button class="btn btn-primary px-8 py-3" wire:click="update">
                {{ __('messages.update_profile') }}
            </x-primary-button>
        </div>
    </div>

    {{-- change password --}}
    <div class="card shadow-sm mt-10">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.change_password') }}</h3>
    </div>
    <div class="card-body">

        {{-- Old Password --}}
        <div class="mb-5 row">
            <label class="col-sm-3 col-form-label required">{{ __('messages.old_password') }}</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input id="old_password" type="password" class="form-control" placeholder="{{ __('messages.old_password') }}"
                        wire:model.defer="old_password" />
                    <span class="input-group-text bg-light border-start-0 toggle-password" data-target="old_password"
                        style="cursor:pointer;">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('old_password')" class="mt-2" />
            </div>
        </div>

        {{-- New Password --}}
        <div class="mb-5 row">
            <label class="col-sm-3 col-form-label required">{{ __('messages.new_password') }}</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input id="new_password" type="password" class="form-control" placeholder="{{ __('messages.new_password') }}"
                        wire:model.defer="new_password" />
                    <span class="input-group-text bg-light border-start-0 toggle-password" data-target="new_password"
                        style="cursor:pointer;">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="mb-5 row">
            <label class="col-sm-3 col-form-label required">{{ __('messages.confirm_password') }}</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input id="confirm_password" type="password" class="form-control"
                        placeholder="{{ __('messages.confirm_password') }}" wire:model.defer="confirm_password" />
                    <span class="input-group-text bg-light border-start-0 toggle-password"
                        data-target="confirm_password" style="cursor:pointer;">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('confirm_password')" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="card-footer text-end">
        <x-primary-button class="btn btn-primary px-8 py-3" wire:click="updatePassword">
            {{ __('messages.change_password') }}
        </x-primary-button>
    </div>
</div>

@push('scripts')
<script>
    // Apply to all toggle-password icons
    document.querySelectorAll('.toggle-password').forEach(function (element) {
        element.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    });
</script>
@endpush

