<div class="card shadow-sm rounded">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
        <h5 class="mb-0">Tree Harvest Summary</h5>

        <div class="d-flex flex-wrap gap-2">
            <!-- Search -->
            <input
                wire:model.live="search"
                type="text"
                class="form-control form-control-sm"
                placeholder="Search by tree tag or species..."
                style="width: 220px;"
            >

            <!-- Species Filter -->
            <select wire:model="filterSpecies" class="form-select form-select-sm" style="width: 180px;">
                <option value="">All Species</option>
                @foreach($speciesList as $species)
                    <option value="{{ $species->id }}">{{ $species->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr class="text-muted small text-uppercase">
                        <th>Tree Tag</th>
                        <th>Species</th>
                        <th>Planted Date</th>
                        <th>Total Fruit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trees as $tree)
                        <tr>
                            <td class="align-middle">
                                <button wire:click="toggleExpand('{{ $tree->uuid }}')" class="btn btn-link p-0 d-inline-flex align-items-center">
                                    <span class="me-2" style="display:inline-block; transition: transform .2s; {{ in_array($tree->uuid, $expanded) ? 'transform: rotate(90deg);' : '' }}">
                                        ▶
                                    </span>
                                    {{ $tree->tree_tag }}
                                </button>
                            </td>
                            <td class="align-middle">{{ $tree->species->name ?? '-' }}</td>
                            <td class="align-middle">{{ $tree->planted_at ? \Carbon\Carbon::parse($tree->planted_at)->format('d M Y') : '-' }}</td>
                            <td class="align-middle">{{ $tree->fruitCountInHarvest($harvestEvent->uuid) }}</td>
                        </tr>

                        @if (in_array($tree->uuid, $expanded))
                            <tr class="table-active">
                                <td colspan="4" class="p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead class="table-light">
                                                <tr class="small text-uppercase text-muted">
                                                    <th class="ps-4">Fruit Tag</th>
                                                    <th>Weight (kg)</th>
                                                    <th>Grade</th>
                                                    <th>Harvested Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($tree->fruits as $fruit)
                                                    <tr>
                                                        <td class="ps-4 small">{{ $fruit->fruit_tag }}</td>
                                                        <td class="small">{{ number_format($fruit->weight, 2) }}</td>
                                                        <td class="small">
                                                            @php
                                                                $grade = strtoupper($fruit->grade ?? '');
                                                                $gradeClassMap = [
                                                                    'A' => 'badge bg-success-subtle text-success',
                                                                    'B' => 'badge bg-primary-subtle text-primary',
                                                                    'C' => 'badge bg-warning-subtle text-warning',
                                                                    'D' => 'badge bg-info-subtle text-info',
                                                                    'E' => 'badge bg-danger-subtle text-danger',
                                                                    'F' => 'badge bg-danger-subtle text-danger',
                                                                ];
                                                                $badgeClass = $gradeClassMap[$grade] ?? 'badge bg-secondary-subtle text-secondary';
                                                            @endphp
                                                            <span class="{{ $badgeClass }}">{{ $grade ?: '-' }}</span>
                                                        </td>
                                                        <td class="small">{{ $fruit->harvested_at ? \Carbon\Carbon::parse($fruit->harvested_at)->format('d M Y') : '-' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="4" class="ps-4 small text-muted">No fruits for this tree.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">No trees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
