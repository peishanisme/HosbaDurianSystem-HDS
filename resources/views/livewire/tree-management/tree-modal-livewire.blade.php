<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="fv-row mb-8">
        <x-input-label for="thumbnail" class="mb-2" :value="__('Thumbnail')" />
        <livewire:components.thumbnail-input />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="species" class="required mb-4" :value="__('Species')" />
        <x-input-select id="species" placeholder="Select Species" wire:model="form.species_id" :options="$speciesOptions" />
        <x-input-error :messages="$errors->get('form.species_id')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="planted_at" class="required mb-2" :value="__('Planted_at')" />
        <x-input-text id="planted_at" type="date" placeholder="Planted_at" wire:model="form.planted_at" />
        <x-input-error :messages="$errors->get('form.planted_at')" />
    </div>

    <div class="fv-row mb-8">
        <div class="d-flex w-100 gap-5">
            <div class="flex-fill">
            <x-input-label for="height" class="required mb-4" :value="__('Height(m)')" />
            <x-input-text id="height" type="number" placeholder="Height(m)" wire:model="form.height" />
            </div>

            <div class="flex-fill">
            <x-input-label for="diameter" class="required mb-4" :value="__('Diameter(m)')" />
            <x-input-text id="diameter" type="number" placeholder="Diameter(m)" wire:model="form.diameter" />
            </div>
        </div>
        <x-input-error :messages="$errors->get('form.height')" />
        <x-input-error :messages="$errors->get('form.diameter')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="flowering_period" class="required mb-2" :value="__('Flowering Period')" />
        <x-input-text id="flowering_period" type="number" placeholder="Flowering_period" wire:model="form.flowering_period" />
        <x-input-error :messages="$errors->get('form.flowering_period')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</x-button>
        <x-button type="submit" class="btn btn-primary"
            wire:click="{{ $form->tree ? 'update' : 'create' }}">{{ $form->tree ? 'Update' : 'Add' }}</x-button>
    @endslot
</x-modal-component>
