<x-modal-component :id="$modalID" :title="$modalTitle">
    <div class="fv-row mb-8">
        <x-input-label for="name" class="required mb-2" :value="__('messages.company_name')" />
        <x-input-text id="company_name" placeholder="{{ __('messages.company_name') }}" wire:model="form.company_name" />
        <x-input-error :messages="$errors->get('form.company_name')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="name" class="mb-2" :value="__('messages.contact_name')" />
        <x-input-text id="contact_name" placeholder="{{ __('messages.contact_name') }}" wire:model="form.contact_name" />
        <x-input-error :messages="$errors->get('form.contact_name')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="phone" class="required mb-2" :value="__('messages.contact_number')" />
        <div class="input-group mt-2">
            <span class="input-group-text bg-light border-0">+60</span>
            <x-input-text id="phone" class="form-control border-start-0" type="tel" name="phone" wire:model="form.contact_number" 
                placeholder="1xxxxxxxxx" value="{{ old('phone') }}" required autofocus autocomplete="tel" />
        </div>
        <x-input-error :messages="$errors->get('form.contact_number')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="email" class="mb-2" :value="__('messages.email')" />
        <x-input-text type="email" id="email" placeholder="{{ __('messages.email') }}" wire:model="form.email" />
        <x-input-error :messages="$errors->get('form.email')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="address" class="mb-2" :value="__('messages.address')" />
        <x-input-textarea id="address" placeholder="{{ __('messages.address') }}" wire:model="form.address"></x-input-textarea>
        <x-input-error :messages="$errors->get('form.address')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        <x-button type="submit" class="btn btn-primary"
            wire:click="{{ $form->buyer ? 'update' : 'create' }}">{{ $form->buyer ? __('messages.update') : __('messages.add') }}</x-button>
    @endslot
</x-modal-component>
