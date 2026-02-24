<x-modal-component :id="$modalID" :title="$this->modalTitle">
    
    <div class="fv-row mb-8">
        <x-input-label for="thumbnail" class="mb-2" :value="__('messages.thumbnail')" />
        <livewire:components.thumbnail-input />
    </div>
    
    <div class="fv-row mb-8">
        <x-input-label for="name" class="required mb-2" :value="__('messages.name')" />
        <x-input-text id="name" placeholder="{{ __('messages.name') }}" wire:model="form.name" />
        <x-input-error :messages="$errors->get('form.name')" />
    </div>

    <div class="fv-row mb-8">
        <div class="d-flex w-100 gap-5">
            <div class="flex-fill">
                <x-input-label for="quantity_per_unit" class="required mb-4" :value="__('messages.quantity_per_unit') . ' (kg/litre)'" />
                <x-input-text id="quantity_per_unit" type="number" min="0" placeholder="{{ __('messages.quantity_per_unit') . ' (kg/litre)' }}" wire:model="form.quantity_per_unit" />
            </div>

            <div class="flex-fill">
                <x-input-label for="price" class="required mb-4" :value="__('messages.price') . ' (RM)'" />
                <x-input-text id="price" type="number" min="0" placeholder="{{ __('messages.price') . ' (RM)' }}" wire:model="form.price" />
            </div>
        </div>
        <x-input-error :messages="$errors->get('form.quantity_per_unit')" />
        <x-input-error :messages="$errors->get('form.price')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="type" class="required mb-4" :value="__('messages.type')" />
        <x-input-select id="type" placeholder="{{ __('messages.select_type') }}" wire:model="form.type" :options="$typeOptions" />
        <x-input-error :messages="$errors->get('form.type')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="description" class="mb-2" :value="__('messages.description')" />
        <x-input-textarea id="description" placeholder="{{ __('messages.description') }}" wire:model="form.description"></x-input-textarea>
        <x-input-error :messages="$errors->get('form.description')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        <x-button type="submit" class="btn btn-primary" wire:click="{{ $form->agrochemical ? 'update' : 'create' }}">{{ $form->agrochemical ? __('messages.update') : __('messages.add') }}</x-button>
    @endslot
</x-modal-component>
