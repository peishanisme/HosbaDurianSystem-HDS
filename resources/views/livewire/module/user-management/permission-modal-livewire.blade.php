<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="mb-5 row">
        <label class="col-sm-3 col-form-label">{{ __('messages.role') }}</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" placeholder="{{ __('messages.role') }}" value="{{ $roleName }}" readonly>
        </div>
    </div>

    <div class="mb-10 row">
        <label class="col-sm-3 col-form-label">{{ __('messages.permissions') }}</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" placeholder="{{ __('messages.search_permission') }}" wire:model.live="search">
        </div>
    </div>

    <div class="overflow-auto" style="max-height: 24rem;">
        @forelse ($this->filteredPermissions as $permission)
            <div class="form-check mb-3">
                <div class="d-flex gap-4">
                    <input class="form-check-input" type="checkbox" value="{{ $permission->id }}"
                        wire:key="{{ $permission->id }}" wire:model.defer="selectedPermissions"
                        @checked(in_array($permission->id, $selectedPermissions)) id="perm-{{ $permission->id }}">
                    <label class="form-check-label d-flex justify-content-between w-100"
                        for="perm-{{ $permission->id }}">
                        {{ $permission->name }}
                    </label>
                </div>

                <hr class="mt-4 mb-0" style="border-top: 1px dotted ;">
            </div>
        @empty
            <p class="text-center text-muted">{{ __('messages.no_permissions_found') }}</p>
        @endforelse
    </div>

    <x-slot:footer>
        <x-button type="button" class="btn btn-primary" wire:click="update">
            {{ __('messages.update_permission') }}
        </x-button>
    </x-slot:footer>

</x-modal-component>
