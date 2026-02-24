<x-modal-component :id="$modalID" :title="$modalTitle">
    @if ($this->hasUnclosedEvent())
        <div class="p-5 text-center">
            <h3 class="fw-semibold text-danger mb-3">
                {{ __('messages.cannot_create_new_harvest_event') }}
            </h3>

            <p class="fs-4" style="color: #6c757d;">
                {{ __('messages.unclosed_harvest_event_warning') }}
            </p>
        </div>
        @slot('footer')
            <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        @endslot
    @else
        <div>
            @if ($harvestEvent)
                <div class="fv-row mb-8">
                    <x-input-label for="name" class="required mb-2" :value="__('messages.event_name')" />
                    <x-input-text id="event_name" placeholder="{{ __('messages.event_name') }}"
                        wire:model="form.event_name" disabled />
                </div>
            @endif

            <div class="fv-row mb-8">
                <x-input-label for="name" class="required mb-2" :value="__('messages.start_date')" />
                <x-input-text id="start_date" placeholder="{{ __('messages.start_date') }}" wire:model="form.start_date"
                    type="date" />
                <x-input-error :messages="$errors->get('form.start_date')" />
            </div>

            <div class="fv-row mb-8">
                <x-input-label for="description" class="mb-2" :value="__('messages.description')" />
                <x-input-textarea id="description" placeholder="{{ __('messages.description') }}"
                    wire:model="form.description"></x-input-textarea>
                <x-input-error :messages="$errors->get('form.description')" />
            </div>

            @slot('footer')
                <x-button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
                <x-button type="submit" class="btn btn-primary"
                    wire:click="{{ $form->harvestEvent ? 'update' : 'create' }}">{{ $form->harvestEvent ? __('messages.update') : __('messages.add') }}</x-button>
            @endslot
        </div>
    @endif

</x-modal-component>
