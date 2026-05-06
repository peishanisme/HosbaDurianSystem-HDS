<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.harvest-event-header :harvestEvent="$harvestEvent" />

    <div class="card shadow-sm rounded">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="mb-0">Harvest Summary by Trees</h5>

            <div class="d-flex flex-wrap gap-2">
                <!-- Search -->
                <input wire:model.live="search" type="text" class="form-control form-control-sm"
                    placeholder="{{ __('messages.search_trees') }}" style="width: 220px;">

                <!-- Year Filter -->
                {{-- <select wire:model.live="filterYear" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">{{ __('messages.any') }}</option>
                    @foreach ($years as $year)
                        <option>{{ $year->year }}</option>
                    @endforeach

                </select> --}}
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th>{{ __('messages.tree_tag') }}</th>
                            <th>{{ __('messages.total_fruits') }}</th>
                            <th>{{ __('messages.total_weight') }}</th>
                            <th>{{ __('messages.total_spoilt') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->trees as $tree)
                            <tr wire:key="tree-{{ $tree->uuid }}">
                                <td class="align-middle">
                                    <button wire:click="toggleExpand('{{ $tree->uuid }}')"
                                        class="btn btn-link p-0 d-inline-flex align-items-center">
                                        <span class="me-2"
                                            style="display:inline-block; transition: transform .2s; {{ in_array($tree->uuid, $expandedTrees) ? 'transform: rotate(90deg);' : '' }}">
                                            ▶
                                        </span>
                                    </button>
                                    <span class="fw-bold">{{ $tree->tree_tag }}</span>
                                </td>
                                <td class="align-middle">
                                    {{ $tree->total_fruits ?? 0 }}
                                </td>
                                <td class="align-middle">
                                    {{ $tree->total_weight ?? 0 }}
                                </td>
                                <td class="align-middle">
                                    {{ $tree->total_spoilt ?? 0 }}
                                </td>
                            </tr>

                            @if (in_array($tree->uuid, $expandedTrees))
                                <tr class="table-active" wire:key="expand-{{ $tree->uuid }}">
                                    <td colspan="4" class="p-0">
                                        <div class="expand-wrapper" wire:key="expand-{{ $tree->uuid }}">

                                            <div class="expand-inner">

                                                <table class="table table-sm mb-0">
                                                    <thead class="table-light">
                                                        <tr class="small text-uppercase text-muted">
                                                            <th>Date</th>
                                                            <th>Total Fruits</th>
                                                            <th>Total Weight</th>
                                                            <th>Total Spoilt</th>
                                                        </tr>
                                                    </thead>
                    
                                                    @php
                                                        $daily = $this->harvestEvent->dailySummaryByTree($tree->uuid);
                                                    @endphp

                                                    <tbody>
                                                        @forelse ($daily as $day)
                                                            @php
                                                                $dayKey = $tree->uuid . '_' . $day->harvest_date;
                                                            @endphp

                                                            <!-- DAY ROW -->
                                                            <tr
                                                                wire:key="day-{{ $tree->uuid }}-{{ $day->harvest_date }}">
                                                                <td>
                                                                    <button
                                                                        wire:click="toggleDay('{{ $tree->uuid }}', '{{ $day->harvest_date }}')"
                                                                        class="btn btn-sm btn-link p-0 me-2">

                                                                        <span
                                                                            style="display:inline-block; transition: transform .2s;
                        {{ in_array($dayKey, $expandedDays) ? 'transform: rotate(90deg);' : '' }}">
                                                                            ▶
                                                                        </span>
                                                                    </button>

                                                                    {{ \Carbon\Carbon::parse($day->harvest_date)->format('d M Y') }}
                                                                </td>

                                                                <td>{{ $day->total_fruits }}</td>
                                                                <td>{{ number_format($day->total_weight, 2) }}</td>
                                                                <td>{{ $day->total_spoilt }}</td>
                                                            </tr>

                                                            <!-- 3RD LAYER (RECORDS) -->
                                                            @if (in_array($dayKey, $expandedDays))
                                                                <tr wire:key="record-{{ $dayKey }}">
                                                                    <td colspan="4" class="bg-light">

                                                                        <div class="ps-4">

                                                                            @php
                                                                                $records = $this->getRecordsByDay(
                                                                                    $tree->uuid,
                                                                                    $day->harvest_date,
                                                                                );
                                                                            @endphp

                                                                            <table class="table table-sm mb-0">
                                                                                <thead>
                                                                                    <tr class="small text-muted">
                                                                                        <th>Fruits</th>
                                                                                        <th>Weight</th>
                                                                                        <th>Spoilt</th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody>
                                                                                    @foreach ($records as $record)
                                                                                        <tr>
                                                                                            <td>{{ $record->num_of_fruits }}
                                                                                            </td>
                                                                                            <td>{{ number_format($record->weight, 2) }}
                                                                                            </td>
                                                                                            <td>
                                                                                                @if ($record->spoilt)
                                                                                                    <span
                                                                                                        class="badge bg-danger">Yes</span>
                                                                                                @else
                                                                                                    <span
                                                                                                        class="badge bg-success">No</span>
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>

                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                            @endif

                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-muted text-center">
                                                                    No records
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>

                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    {{ __('messages.no_harvest_records_found_for_this_tree') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <livewire:components.generate-report-modal model="App\Models\HarvestEvent" :harvestEvent="$harvestEvent" />

    <livewire:module.post-harvest.harvest-qr-code-modal-livewire />

</div>

@push('styles')
    <style>
        .expand-wrapper .expand-inner {
            padding: 1rem;
        }

        .expand-wrapper {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transform: scaleY(0.95);
            transform-origin: top;
            transition: all 0.25s ease;
        }

        tr.table-active .expand-wrapper {
            max-height: 1000px;
            opacity: 1;
            transform: scaleY(1);
        }
    </style>
@endpush
