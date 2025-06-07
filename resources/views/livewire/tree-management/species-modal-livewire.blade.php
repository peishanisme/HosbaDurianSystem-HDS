<x-modal-component :id="$modalID" :title="$modalTitle">
    <div class="fv-row mb-8">
        <x-input-label for="name" class="required mb-2" :value="__('Name')" />
        <x-input-text id="name" placeholder="Name" wire:model="form.name" />
        <x-input-error :messages="$errors->get('form.name')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="code" class="required mb-2" :value="__('Code')" />
        <x-input-text id="code" placeholder="Code" wire:model="form.code" />
        <x-input-error :messages="$errors->get('form.code')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="description" class="mb-2" :value="__('Description')" />
        <x-input-textarea id="description" placeholder="Description" wire:model="form.description"></x-input-textarea>
        <x-input-error :messages="$errors->get('form.description')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="active" class="required mb-4" :value="__('Is Active?')" />
        <div class="d-flex w-full gap-5">
            <x-input-radio id="is_activeYes" name="is_active" value="1" model="form.is_active"
                label="Yes" />
            <x-input-radio id="is_activeNo" name="is_active" value="0" model="form.is_active"
                label="No" />
        </div>
        <x-input-error :messages="$errors->get('form.is_active')" />
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</x-button>
        <x-button type="submit" class="btn btn-primary" wire:click="{{ $form->species ? 'update' : 'create' }}">{{ $form->species ? 'Update' : 'Add' }}</x-button>
    @endslot
</x-modal-component>
