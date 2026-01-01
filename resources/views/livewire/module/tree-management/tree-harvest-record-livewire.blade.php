<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.tree-details-header :tree="$tree" />

    <div class="card shadow-sm rounded">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="mb-0">Harvest Records for {{ $tree->tree_tag }}</h5>

            <div class="d-flex flex-wrap gap-2">
                <!-- Search -->
                <input wire:model.live="search" type="text" class="form-control form-control-sm"
                    placeholder="Search harvest name..." style="width: 220px;">

                <!-- Year Filter -->
                <select wire:model.live="filterYear" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">All Years</option>
                    @foreach ($years as $year)
                        <option>{{ $year->year }}</option> 
                    @endforeach

                </select>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th>Harvest Event</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Fruits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($harvests as $harvest)
                            @php
                                $fruits = $harvest->fruits;
                                $totalFruits = $fruits->count();
                            @endphp
                            <tr>
                                <td class="align-middle">
                                    <button wire:click="toggleExpand('{{ $harvest->uuid }}')"
                                        class="btn btn-link p-0 d-inline-flex align-items-center">
                                        <span class="me-2"
                                            style="display:inline-block; transition: transform .2s; {{ in_array($harvest->uuid, $expanded) ? 'transform: rotate(90deg);' : '' }}">
                                            ▶
                                        </span>
                                    </button>
                                    <span class="fw-bold">{{ $harvest->event_name }}</span>
                                </td>
                                <td class="align-middle">
                                    {{ \Carbon\Carbon::parse($harvest->start_date)->format('d M Y') }}</td>
                                <td class="align-middle">
                                    {{ $harvest->end_date ? \Carbon\Carbon::parse($harvest->end_date)->format('d M Y') : '-' }}
                                </td>
                                <td class="align-middle">{{ $totalFruits }}</td>
                            </tr>

                            @if (in_array($harvest->uuid, $expanded))
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
                                                    @forelse ($fruits as $fruit)
                                                        <tr>
                                                            <td class="ps-4 small">{{ $fruit->fruit_tag }}</td>
                                                            <td class="small">{{ number_format($fruit->weight, 2) }}
                                                            </td>
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
                                                                    $badgeClass =
                                                                        $gradeClassMap[$grade] ??
                                                                        'badge bg-secondary-subtle text-secondary';
                                                                @endphp
                                                                <span
                                                                    class="{{ $badgeClass }}">{{ $grade ?: '-' }}</span>
                                                            </td>
                                                            <td class="small">
                                                                {{ $fruit->harvested_at ? \Carbon\Carbon::parse($fruit->harvested_at)->format('d M Y') : '-' }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="ps-4 small text-muted">
                                                                No fruits for this harvest.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No harvest records found for this
                                    tree.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
