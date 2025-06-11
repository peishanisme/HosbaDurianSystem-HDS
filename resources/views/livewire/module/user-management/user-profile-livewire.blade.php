<div class="container">
    {{-- profile details --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Profile Details</h3>
        </div>
        <div class="card-body">

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="Name" wire:model="form.name">
                    <x-input-error :messages="$errors->get('form.name')" />
                </div>
            </div>

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" placeholder="Email" wire:model="form.email">
                    <x-input-error :messages="$errors->get('form.email')" />
                </div>
            </div>

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label">Phone Number</label>
                <div class="col-sm-9">
                    <input type="tel" class="form-control" placeholder="Phone Number" wire:model="form.phone">
                    <x-input-error :messages="$errors->get('form.phone')" />
                </div>
            </div>

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label">Role</label>
                <div class="col-sm-9">
                    <input type="role" class="form-control" placeholder="Role" wire:model="role" disabled>
                </div>
            </div>

        </div>
        <div class="card-footer text-end">
            <x-primary-button class="btn btn-primary px-8 py-3">
                {{ __('Update Profile') }}
            </x-primary-button>
        </div>
    </div>

    {{-- change password --}}
    <div class="card shadow-sm mt-10">
        <div class="card-header">
            <h3 class="card-title">Change Password</h3>
        </div>
        <div class="card-body">

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label required">Old Password</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="Old password" wire:model="">
                    <x-input-error :messages="$errors->get('')" />
                </div>
            </div>

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label required">New Password</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" placeholder="New password" wire:model="">
                    <x-input-error :messages="$errors->get('')" />
                </div>
            </div>

            <div class="mb-5 row">
                <label class="col-sm-3 col-form-label required">Confirm Password</label>
                <div class="col-sm-9">
                    <input type="tel" class="form-control" placeholder="Confirm Password" wire:model="">
                    <x-input-error :messages="$errors->get('')" />
                </div>
            </div>

        </div>
        <div class="card-footer text-end">
            <x-primary-button class="btn btn-primary px-8 py-3">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </div>
</div>
