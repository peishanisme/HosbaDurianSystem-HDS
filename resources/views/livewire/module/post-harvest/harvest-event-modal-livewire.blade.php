<x-modal-component :id="$modalID" :title="$modalTitle">
    @if ($harvestEvent)
        <div class="fv-row mb-8">
            <x-input-label for="name" class="required mb-2" :value="__('Event Name')" />
            <x-input-text id="event_name" placeholder="Event Name" wire:model="form.event_name" />
            <x-input-error :messages="$errors->get('form.event_name')" />
        </div>
    @endif

    <div class="fv-row mb-8">
        <x-input-label for="name" class="required mb-2" :value="__('Start Date')" />
        <x-input-text id="start_date" placeholder="Start Date" wire:model="form.start_date" type="date" />
        <x-input-error :messages="$errors->get('form.start_date')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="description" class="mb-2" :value="__('Description')" />
        <x-input-textarea id="description" placeholder="Description" wire:model="form.description"></x-input-textarea>
        <x-input-error :messages="$errors->get('form.description')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</x-button>
        <x-button type="submit" class="btn btn-primary"
            wire:click="{{ $form->harvestEvent ? 'update' : 'create' }}">{{ $form->harvestEvent ? 'Update' : 'Add' }}</x-button>
    @endslot
</x-modal-component>
