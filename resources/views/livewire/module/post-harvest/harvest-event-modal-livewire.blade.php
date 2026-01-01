<x-modal-component :id="$modalID" :title="$modalTitle">
    @if ($hasUnclosedEvent)
        <div class="p-5 text-center">
            <h3 class="fw-semibold text-danger mb-3">
                Cannot Create New Harvest Event
            </h3>

            <p class="fs-4" style="color: #6c757d;">
                There is an existing harvest event that has not been closed.<br>
                Please close the current event before creating a new one.
            </p>
        </div>
        @slot('footer')
            <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</x-button>
        @endslot
    @else
        <div>
            @if ($harvestEvent)
                <div class="fv-row mb-8">
                    <x-input-label for="name" class="required mb-2" :value="__('Event Name')" />
                    <x-input-text id="event_name" placeholder="Event Name" wire:model="form.event_name" disabled />
                </div>
            @endif

            <div class="fv-row mb-8">
                <x-input-label for="name" class="required mb-2" :value="__('Start Date')" />
                <x-input-text id="start_date" placeholder="Start Date" wire:model="form.start_date" type="date" />
                <x-input-error :messages="$errors->get('form.start_date')" />
            </div>

            <div class="fv-row mb-8">
                <x-input-label for="description" class="mb-2" :value="__('Description')" />
                <x-input-textarea id="description" placeholder="Description"
                    wire:model="form.description"></x-input-textarea>
                <x-input-error :messages="$errors->get('form.description')" />
            </div>

            @slot('footer')
                <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</x-button>
                <x-button type="submit" class="btn btn-primary"
                    wire:click="{{ $form->harvestEvent ? 'update' : 'create' }}">{{ $form->harvestEvent ? 'Update' : 'Add' }}</x-button>
            @endslot
        </div>
    @endif
</x-modal-component>
