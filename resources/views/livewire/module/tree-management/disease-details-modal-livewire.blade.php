<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="mb-8 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Affected Trees</h5>

        <!-- Filter Dropdown -->
        <select wire:model.defer="statusFilter" wire:change="loadTrees" class="form-select w-auto">
            <option value="">All Status</option>
            <option value="Severe">Severe</option>
            <option value="Medium">Medium</option>
            <option value="Recovered">Recovered</option>
        </select>
    </div>

    <!-- Tree Listing -->
    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">

        @if ($trees && !$noTree)
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Tree Tag</th>
                        <th>Species</th>
                        <th wire:click="sortBy('status')" style="cursor:pointer;">
                            Status
                            @if ($sortField === 'status')
                                <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                            @endif
                        </th>
                        <th>Last Checked</th>
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
                                <span
                                    class="badge
                    @if ($tree->pivot->status === 'Severe') badge-light-danger
                    @elseif($tree->pivot->status === 'Medium') badge-light-warning
                    @elseif($tree->pivot->status === 'Recovered') badge-light-success @endif">
                                    {{ ucfirst($tree->pivot->status ?? '-') }}
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
                        <th>Tree Tag</th>
                        <th>Species</th>
                        <th wire:click="sortBy('status')" style="cursor:pointer;">
                            Status
                            @if ($sortField === 'status')
                                <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                            @endif
                        </th>
                        <th>Last Checked</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No trees affected by this disease.
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</x-button>
    @endslot
</x-modal-component>
