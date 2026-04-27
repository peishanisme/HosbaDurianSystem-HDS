<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="mb-5 row">
        <label class="col-sm-3 col-form-label">{{ __('messages.label') }}</label>

        <div class="col-sm-9">

            <!-- Select + Add Button -->
            <div class="d-flex gap-2">
                <select class="form-select" wire:model.lazy="selectedLabel">
                    <option value="">{{ __('messages.select_label') }}</option>
                    @foreach ($labelsOptions as $label)
                        <option value="{{ $label->id }}">{{ $label->name }}</option>
                    @endforeach
                </select>

                <!-- + Button -->
                <button type="button" class="btn btn-light-primary" wire:click="$toggle('showCreateLabel')">
                    +
                </button>
            </div>

            <!-- Create Label Form -->
            @if ($showCreateLabel)
                <div class="mt-4 p-3 border rounded bg-light">
                    <div class="d-flex gap-4 mb-4">

                        <!-- Color Picker -->
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <input type="color" wire:model.live="newLabelColor"
                                style="width: 50px; height: 40px; border: none;">

                            <span class="badge" style="background-color: {{ $newLabelColor }}">
                                Preview
                            </span>
                        </div>
                        <!-- Label Name -->
                        <div class="flex-fill">
                            <input type="text" class="form-control" placeholder="New label name"
                                wire:model.defer="newLabelName">
                        </div>

                    </div>
                    <x-input-error :messages="$errors->get('newLabelName')" class="mb-3" />
                    <x-input-error :messages="$errors->get('newLabelColor')" class="mb-3" />

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary" wire:click="createLabel">
                            Create
                        </button>

                        <button class="btn btn-sm btn-light" wire:click="$set('showCreateLabel', false)">
                            Cancel
                        </button>
                    </div>

                </div>
            @endif

        </div>
    </div>

    <div class="mb-10 row">
        <label class="col-sm-3 col-form-label">{{ __('messages.tree_tag') }}</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" placeholder="{{ __('messages.search_tree_tag') }}"
                wire:model.live="search">
        </div>
    </div>

    <div class="overflow-auto" style="max-height: 24rem;">
        @forelse ($this->filteredTrees as $tree)
            <div class="form-check my-3">
                <div class="d-flex align-items-center gap-3">
                    <input class="form-check-input mt-1" type="checkbox" value="{{ $tree->id }}"
                        wire:key="tree-{{ $tree->id }}" wire:model.defer="selectedTrees"
                        id="tree-{{ $tree->id }}">

                    <label class="form-check-label w-100" for="tree-{{ $tree->id }}">
                        <div class="d-flex justify-content-between align-items-start">

                            <!-- Tree Tag -->
                            <span class="fw-semibold">
                                {{ $tree->tree_tag }}
                            </span>

                            <!-- Labels -->
                            <div class="d-flex flex-wrap gap-1 justify-content-end">
                                @foreach ($tree->labels as $label)
                                    <x-tree-label-badge :label="$label->name" :color="$label->color" />
                                @endforeach
                            </div>

                        </div>
                    </label>

                </div>

                <hr class="mt-4 mb-0" style="border-top: 1px dotted;">
            </div>
        @empty
            <p class="text-center text-muted">{{ __('messages.no_trees_found') }}</p>
        @endforelse

    </div>

    <div class="mt-3">
        {{ $this->filteredTrees->links() }}
    </div>

    <x-slot:footer>
        <x-button type="button" class="btn btn-primary" wire:click="update">
            {{ __('messages.update_labels') }}
        </x-button>
    </x-slot:footer>

</x-modal-component>
