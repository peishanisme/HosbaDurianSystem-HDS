<x-modal-component :id="$modalID" :title="$modalTitle">
    <div class="fv-row mb-8">
        <x-input-label for="name" class="required mb-2" :value="__('messages.name')" />
        <x-input-text id="name" placeholder={{ __('messages.name') }} wire:model="form.name" />
        <x-input-error :messages="$errors->get('form.name')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="email" class="required mb-2" :value="__('messages.email')" />
        <x-input-text id="email" placeholder={{ __('messages.email') }} wire:model="form.email" />
        <x-input-error :messages="$errors->get('form.email')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="phone" class="required mb-2" :value="__('messages.phone_number')" />
        <div class="input-group mt-2">
            <span class="input-group-text bg-light border-0">+60</span>
            <x-input-text id="phone" class="form-control border-start-0" type="tel" name="phone" wire:model="form.phone" 
                placeholder="1xxxxxxxxx" value="{{ old('phone') }}" required autofocus autocomplete="tel" />
        </div>
        <x-input-error :messages="$errors->get('form.phone')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="role" class="required mb-4" :value="__('messages.role')" />
        <x-input-select id="role" placeholder="{{ __('messages.select_role') }}" wire:model="form.role" :options="$roleOptions" />
        <x-input-error :messages="$errors->get('form.role')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="active" class="required mb-4" :value="__('messages.is_active')" />
        <div class="d-flex w-full gap-5">
            <x-input-radio id="is_activeYes" name="is_active" value="1" model="form.is_active" label="{{ __('messages.yes') }}" />
            <x-input-radio id="is_activeNo" name="is_active" value="0" model="form.is_active" label="{{ __('messages.no') }}" />
        </div>
        <x-input-error :messages="$errors->get('form.is_active')" />
    </div>

    <x-alert />

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        <x-button type="submit" class="btn btn-primary"
            wire:click="{{ $form->user ? 'update' : 'create' }}">{{ $form->user ? __('messages.update') : __('messages.add') }}</x-button>
    @endslot
</x-modal-component>
