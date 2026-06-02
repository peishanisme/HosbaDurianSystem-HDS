<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="fv-row mb-8">
        <x-input-label for="date" class="mb-2" :value="__('messages.date')" />
        <x-input-text id="date" type="date" placeholder="date" wire:model="form.date" />
        <x-input-error :messages="$errors->get('form.date')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="species" class=" mb-4" :value="__('messages.species')" />
        <x-input-select id="species" placeholder="Select Species" wire:model="form.species_id" :options="$speciesOptions" />
        <x-input-error :messages="$errors->get('form.species_id')" />
    </div>

    <div class="fv-row mb-8">
        <div class="d-flex w-100 gap-5">
            <div class="flex-fill">
                <x-input-label for="grade" class=" mb-4" :value="__('messages.grade')" />
                <x-input-select id="grade" placeholder="Select Grade" wire:model="form.grade" :options="$gradeOptions" />
            </div>

            <div class="flex-fill">
                <x-input-label for="weight" class="required mb-4" :value="__('messages.weight')" />
                <x-input-text id="weight" type="number" placeholder="Weight" wire:model="form.weight" />
            </div>
        </div>
        <x-input-error :messages="$errors->get('form.grade')" />
        <x-input-error :messages="$errors->get('form.weight')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        <x-button type="submit" class="btn btn-primary" wire:click="update">{{ __('messages.update') }}</x-button>
    @endslot
</x-modal-component>
