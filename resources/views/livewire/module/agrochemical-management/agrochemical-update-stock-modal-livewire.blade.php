<x-modal-component :id="$modalID" :title="$modalTitle">
    <div class="fv-row mb-8">
        <x-input-label for="name" class="mb-2" :value="__('Name')" />
        <x-input-text id="name" placeholder="Name" wire:model="form.name" disabled/>
        <x-input-error :messages="$errors->get('form.name')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="date" class="mb-2 required" :value="__('Date')" />
        <x-input-text id="date" placeholder="date" wire:model="form.date" type="date"/>
        <x-input-error :messages="$errors->get('form.date')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="quantity" class="mb-2 required" :value="__('Quantity')" />
        <x-input-text id="quantity" placeholder="Quantity" wire:model="form.quantity"/>
        <x-input-error :messages="$errors->get('form.quantity')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="description" class="mb-2" :value="__('Description')" />
        <x-input-textarea id="description" placeholder="Description" wire:model="form.description"></x-input-textarea>
        <x-input-error :messages="$errors->get('form.description')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</x-button>
        <x-button type="submit" class="btn btn-primary"
            wire:click="{{ $form->stock ? 'update' : 'create' }}">{{ $form->stock ? 'Update' : 'Add' }}</x-button>
    @endslot

</x-modal-component>
