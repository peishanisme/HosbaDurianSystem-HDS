<x-modal-component :id="$modalID" :title="$modalTitle">
    <div class="fv-row mb-8">
        <x-input-label for="name" class="mb-2" :value="__('messages.name')" />
        <x-input-text id="name" placeholder="{{ __('messages.name') }}" wire:model="form.name" disabled/>
        <x-input-error :messages="$errors->get('form.name')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="date" class="mb-2 required" :value="__('messages.date')" />
        <x-input-text id="date" placeholder="{{ __('messages.date') }}" wire:model="form.date" type="date"/>
        <x-input-error :messages="$errors->get('form.date')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="quantity" class="mb-2 required" :value="__('messages.quantity')" />
        <x-input-text id="quantity" placeholder="{{ __('messages.quantity') }}" wire:model="form.quantity"/>
        <x-input-error :messages="$errors->get('form.quantity')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="description" class="mb-2" :value="__('messages.description')" />
        <x-input-textarea id="description" placeholder="{{ __('messages.description') }}" wire:model="form.description"></x-input-textarea>
        <x-input-error :messages="$errors->get('form.description')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        <x-button type="submit" class="btn btn-primary"
            wire:click="{{ $form->stock ? 'update' : 'create' }}">{{ $form->stock ? __('messages.update') : __('messages.add') }}</x-button>
    @endslot

</x-modal-component>
