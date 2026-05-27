<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="fv-row mb-8">
        <x-input-label for="tree" class="required mb-4" :value="__('messages.tree')" />
        <x-input-select id="tree" placeholder="Select Tree" wire:model="form.tree_uuid" :options="$treeOptions" />
        <x-input-error :messages="$errors->get('form.tree_uuid')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="harvest_date" class="mb-2 required" :value="__('messages.harvested_date')" />
        <x-input-text id="harvest_date" type="date" placeholder="harvest_date" wire:model="form.harvest_date" />
        <x-input-error :messages="$errors->get('form.harvest_date')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="num_of_fruits" class="required mb-4" :value="__('messages.num_of_fruits')" />
        <x-input-text id="num_of_fruits" placeholder="Number of Fruits" wire:model="form.num_of_fruits" />
        <x-input-error :messages="$errors->get('form.num_of_fruits')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="spoilt" class="required mb-4" :value="__('messages.spoilt')" />
        <div class="d-flex w-full gap-5">
            <x-input-radio id="spoilt" name="spoilt" value="1" model="form.spoilt"
                label="{{ __('messages.yes') }}" />
            <x-input-radio id="spoilt" name="spoilt" value="0" model="form.spoilt"
                label="{{ __('messages.no') }}" />
        </div>
        <x-input-error :messages="$errors->get('form.spoilt')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        <x-button type="submit" class="btn btn-primary" wire:click="update">{{ __('messages.update') }}</x-button>
    @endslot
</x-modal-component>
