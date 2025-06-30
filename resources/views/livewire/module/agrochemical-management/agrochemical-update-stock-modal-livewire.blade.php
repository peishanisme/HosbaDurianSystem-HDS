<x-modal-component :id="$modalID" :title="$modalTitle">
    <div class="fv-row mb-8">
        <x-input-label for="name" class="required mb-2" :value="__('Name')" />
        <x-input-text id="name" placeholder="Name" wire:model="form.name" />
        <x-input-error :messages="$errors->get('form.name')" />
    </div>

    <div class="fv-row mb-8">
        <div class="d-flex w-100 gap-5">
            <div class="flex-fill">
                <x-input-label for="quantity_per_unit" class="required mb-4" :value="__('Quantity Per Unit(kg/litre)')" />
                <x-input-text id="quantity_per_unit" type="number" min="0"
                    placeholder="Quantity per unit (kg/litre)" wire:model="form.quantity_per_unit" />
            </div>

            <div class="flex-fill">
                <x-input-label for="price" class="required mb-4" :value="__('Price(RM)')" />
                <x-input-text id="price" type="number" min="0" placeholder="Price(RM)"
                    wire:model="form.price" />
            </div>
        </div>
        <x-input-error :messages="$errors->get('form.quantity_per_unit')" />
        <x-input-error :messages="$errors->get('form.price')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="description" class="mb-2" :value="__('Description')" />
        <x-input-textarea id="description" placeholder="Description" wire:model="form.description"></x-input-textarea>
        <x-input-error :messages="$errors->get('form.description')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</x-button>
        <x-button type="submit" class="btn btn-primary"
            wire:click="{{ $form->agrochemical ? 'update' : 'create' }}">{{ $form->agrochemical ? 'Update' : 'Add' }}</x-button>
    @endslot

</x-modal-component>
