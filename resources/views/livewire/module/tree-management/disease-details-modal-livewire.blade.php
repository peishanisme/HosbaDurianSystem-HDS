<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="mb-8 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('messages.affected_trees') }}</h5>

        <!-- Filter Dropdown -->
        <select wire:model.defer="statusFilter" wire:change="loadTrees" class="form-select w-auto">
            <option value="">{{ __('messages.any') }}</option>
            <option value="Severe">{{ __('messages.severe') }}</option>
            <option value="Medium">{{ __('messages.medium') }}</option>
            <option value="Recovered">{{ __('messages.recovered') }}</option>
        </select>
    </div>

    <!-- Tree Listing -->
    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">

        @if ($trees && !$noTree)
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>{{ __('messages.tree_tag') }}</th>
                        <th>{{ __('messages.species') }}</th>
                        <th wire:click="sortBy('status')" style="cursor:pointer;">
                            {{ __('messages.status') }}
                            @if ($sortField === 'status')
                                <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                            @endif
                        </th>
                        <th>{{ __('messages.last_checked') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($trees as $tree)
                        <tr class="py-5">
                            <td class="py-7">
                                <a href="{{ route('tree.show', $tree->id) }}"
                                    class="fw-semibold text-dark text-hover-primary">
                                    {{ $tree->tree_tag }}
                                </a>
                            </td>
                            <td class="py-7">{{ $tree->species->name ?? '-' }}</td>
                            <td class="py-7">
                                @php
                                    $status = $tree->pivot->status; 

                                    $statusClassMap = [
                                        'Severe' => 'badge-light-danger',
                                        'Medium' => 'badge-light-warning',
                                        'Recovered' => 'badge-light-success',
                                    ];
                                @endphp

                                <span class="badge {{ $statusClassMap[$status] ?? 'badge-light-secondary' }}">
                                    {{ $status ? __('messages.tree_status.' . $status) : '-' }}
                                </span>

                            </td>
                            <td class="py-7">{{ $tree->updated_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                    @endforelse

                </tbody>
            </table>
        @endif
        @if ($noTree)
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>{{ __('messages.tree_tag') }}</th>
                        <th>{{ __('messages.species') }}</th>
                        <th wire:click="sortBy('status')" style="cursor:pointer;">
                            {{ __('messages.status') }}
                            @if ($sortField === 'status')
                                <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                            @endif
                        </th>
                        <th>Last Checked</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            {{ __('messages.no_trees_affected_by_this_disease') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</x-button>
    @endslot
</x-modal-component>
