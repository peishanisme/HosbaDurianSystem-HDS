<x-modal-component :id="$modalID" :title="$modalTitle">
    <div class="fv-row mb-8">
        <x-input-label for="name" class="required mb-2" :value="__('Name')" />
        <x-input-text id="name" placeholder="Name" wire:model="form.name" />
        <x-input-error :messages="$errors->get('form.name')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="email" class="mb-2" :value="__('Email')" />
        <x-input-text id="email" placeholder="Email" wire:model="form.email" />
        <x-input-error :messages="$errors->get('form.email')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="phone" class="required mb-2" :value="__('Phone Number')" />
        <x-input-text type="tel" id="phone" placeholder="Phone Number" wire:model="form.phone" />
        <x-input-error :messages="$errors->get('form.phone')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="role" class="required mb-4" :value="__('Role')" />
        <x-input-select id="role" placeholder="Select Role" wire:model="form.role" :options="$roleOptions" />
        <x-input-error :messages="$errors->get('form.role')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="active" class="required mb-4" :value="__('Is Active?')" />
        <div class="d-flex w-full gap-5">
            <x-input-radio id="is_activeYes" name="is_active" value="1" model="form.is_active"
                label="Yes" />
            <x-input-radio id="is_activeNo" name="is_active" value="0" model="form.is_active"
                label="No" />
        </div>
        <x-input-error :messages="$errors->get('form.is_active')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</x-button>
        <x-button type="submit" class="btn btn-primary" wire:click="{{ $form->user ? 'update' : 'create' }}">{{ $form->user ? 'Update' : 'Add' }}</x-button>
    @endslot
</x-modal-component>
