<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Affected Trees</h5>

        <!-- Filter Dropdown -->
        <select wire:model.live="statusFilter" class="form-select w-auto">
            <option value="">All Status</option>
            <option value="Severe">Severe</option>
            <option value="Medium">Medium</option>
            <option value="Recovered">Recovered</option>
        </select>
    </div>

    <!-- Tree Listing -->
    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
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
                        <td class="py-7">{{ $tree->tree_tag }}</td>
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
                    <tr>
                        <td colspan="4" class="text-center">No trees found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</x-button>
    @endslot
</x-modal-component>
