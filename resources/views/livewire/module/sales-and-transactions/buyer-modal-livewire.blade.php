<x-modal-component :id="$modalID" :title="$modalTitle">
    <div class="fv-row mb-8">
        <x-input-label for="name" class="required mb-2" :value="__('Company Name')" />
        <x-input-text id="company_name" placeholder="Company Name" wire:model="form.company_name" />
        <x-input-error :messages="$errors->get('form.company_name')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="name" class="mb-2" :value="__('Contact Name')" />
        <x-input-text id="contact_name" placeholder="Contact Name" wire:model="form.contact_name" />
        <x-input-error :messages="$errors->get('form.contact_name')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="name" class="required mb-2" :value="__('Contact Number')" />
        <x-input-text id="contact_number" placeholder="Contact Number" wire:model="form.contact_number"  maxlength="11"/>
        <x-input-error :messages="$errors->get('form.contact_number')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="email" class="mb-2" :value="__('Email')" />
        <x-input-text type="email" id="email" placeholder="Email" wire:model="form.email" />
        <x-input-error :messages="$errors->get('form.email')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="address" class="mb-2" :value="__('Address')" />
        <x-input-textarea id="address" placeholder="Address" wire:model="form.address"></x-input-textarea>
        <x-input-error :messages="$errors->get('form.address')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</x-button>
        <x-button type="submit" class="btn btn-primary"
            wire:click="{{ $form->buyer ? 'update' : 'create' }}">{{ $form->buyer ? 'Update' : 'Add' }}</x-button>
    @endslot
</x-modal-component>
