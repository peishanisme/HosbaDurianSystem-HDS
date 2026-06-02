<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.tree-details-header :tree="$tree" />

    <div class="card shadow-sm rounded">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('messages.tree_harvest_records') }} - {{ $tree->tree_tag }}</h5>

            <div class="d-flex flex-wrap gap-2">
                <!-- Search -->
                <input wire:model.live="search" type="text" class="form-control form-control-sm"
                    placeholder="{{ __('messages.search_harvest_events') }}" style="width: 220px;">

                <!-- Year Filter -->
                <select wire:model.live="filterYear" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">{{ __('messages.any') }}</option>
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
                            <th>{{ __('messages.harvest_event') }}</th>
                            <th>{{ __('messages.start_date') }}</th>
                            <th>{{ __('messages.end_date') }}</th>
                            <th>{{ __('messages.total_fruits') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($harvests as $harvest)
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
                                <td class="align-middle">{{ $harvest->totalFruitsForTree($tree->uuid) }}</td>
                            </tr>

                            @if (in_array($harvest->uuid, $expanded))
                                <tr class="table-active">
                                    <td colspan="4" class="p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0">
                                                <thead class="table-light">
                                                    <tr class="small text-uppercase text-muted">
                                                        <th class="ps-4">{{ __('messages.harvested_date') }}</th>
                                                        <th>{{ __('messages.num_of_fruits') }}</th>
                                                        <th>{{ __('messages.weight') }}(kg)</th>
                                                        <th>{{ __('messages.spoilt') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($harvest->harvestRecordsForTree($tree->uuid)->get() as $harvestRecord)
                                                        <tr>
                                                            <td class="ps-4 small">
                                                                {{ $harvestRecord->harvest_date ? \Carbon\Carbon::parse($harvestRecord->harvest_date)->format('d M Y') : '-' }}
                                                            </td>
                                                            <td class="small">
                                                                {{ $harvestRecord->num_of_fruits ?? '-' }}
                                                            </td>
                                                            <td class="small">
                                                                {{ $harvestRecord->weight ?? '-' }}kg
                                                            </td>
                                                            <td class="small">
                                                                @php
                                                                    $status = $harvestRecord->spoilt
                                                                        ? 'Spoilt'
                                                                        : 'Not Spoilt';
                                                                    $gradeClassMap = [
                                                                        'Spoilt' =>
                                                                            'badge bg-danger-subtle text-danger',
                                                                        'Not Spoilt' =>
                                                                            'badge bg-success-subtle text-success',
                                                                    ];
                                                                    $badgeClass =
                                                                        $gradeClassMap[$status] ??
                                                                        'badge bg-secondary-subtle text-secondary';
                                                                @endphp
                                                                <span class="{{ $badgeClass }}">
                                                                    {{ __('messages.spoilt_status.' . $status) }}
                                                                </span>
                                                            </td>
                                                        </tr>

                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="ps-4 small text-muted">
                                                                {{ __('messages.no_fruits_for_this_harvest') }}
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
                                <td colspan="4" class="text-center text-muted">
                                    {{ __('messages.no_harvest_records_found_for_this_tree') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <livewire:module.post-harvest.harvest-qr-code-modal-livewire />

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-tree-toggle]').forEach(button => {
                button.addEventListener('click', function(e) {
                    const uuid = this.dataset.uuid;
                    const expandable = document.querySelector(`#expandable-${uuid}`);
                    const arrow = this.querySelector('span');

                    if (expandable) {
                        expandable.classList.toggle('open');
                    }

                    // Smoothly rotate the arrow
                    if (arrow) {
                        const isExpanded = expandable.classList.contains('open');
                        arrow.style.transition = 'transform 0.2s ease';
                        arrow.style.transform = isExpanded ? 'rotate(90deg)' : 'rotate(0deg)';
                    }

                    // Notify Livewire to persist expanded state
                    Livewire.dispatch('call', {
                        method: 'toggleTree',
                        params: [uuid]
                    });
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .expandable {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .expandable.open {
            max-height: 1000px;
            transition: max-height 0.5s ease-in;
        }

        .expandable .inner {
            padding: 1rem;
            background-color: #f9f9f9;
        }
    </style>
@endpush
