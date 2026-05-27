<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.harvest-event-header :harvestEvent="$harvestEvent" />
    <div class="card shadow-sm rounded">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="mb-0">Harvest Grades Summary</h5>

            <div class="d-flex flex-wrap gap-2">
                {{-- DATE PICKER --}}
                <!-- Search -->
                {{-- <input wire:model.live="search" type="text" class="form-control form-control-sm"
                    placeholder="{{ __('messages.search_trees') }}" style="width: 220px;"> --}}

            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.total_weight') }}</th>
                            {{-- <th>{{ __('messages.total_spoilt') }}</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->harvestEvent->harvestGradeSummary() as $summary)

                            @php
                                $dateKey = $summary->date;
                            @endphp

                            <!-- PARENT ROW -->
                            <tr wire:key="summary-{{ $summary->date }}">
                                <td class="align-middle">

                                    <button wire:click="toggleDate('{{ $summary->date }}')"
                                        class="btn btn-link p-0 d-inline-flex align-items-center">

                                        <span class="me-2"
                                            style="display:inline-block; transition: transform .2s;
                        {{ in_array($dateKey, $expandedDates) ? 'transform: rotate(90deg);' : '' }}">
                                            ▶
                                        </span>
                                    </button>

                                    <span class="fw-bold">
                                        {{ \Carbon\Carbon::parse($summary->date)->format('d M Y') }}
                                    </span>
                                </td>

                                <td class="align-middle fw-semibold">
                                    {{ number_format($summary->total_weight, 2) }} kg
                                </td>
                            </tr>

                            <!-- EXPANDED CHILD -->
                            @if (in_array($dateKey, $expandedDates))
                                <tr wire:key="expand-{{ $summary->date }}" class="table-active">
                                    <td colspan="2" class="p-0">

                                        <div class="p-3">

                                            <table class="table table-sm table-row-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr class="small text-uppercase text-muted">
                                                        <th>Grade</th>
                                                        <th>Species</th>
                                                        <th>Weight</th>
                                                        <th width="120">Actions</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @foreach ($this->harvestEvent->harvestGradeDetails($summary->date) as $record)
                                                        <tr wire:key="record-{{ $record->id }}">

                                                            <td>
                                                                {{ $record->grade ?? '-' }}
                                                            </td>

                                                            <td>
                                                                {{ $record->species?->name ?? '-' }}
                                                            </td>

                                                            <td>
                                                                {{ number_format($record->weight, 2) }} kg
                                                            </td>

                                                            <td>
                                                                <div class="d-flex gap-2 flex-wrap">
                                                                    <x-table-com-button label1="Edit"
                                                                        modal="harvestGradeEditModal"
                                                                        dispatch1="editHarvestGrade"
                                                                        dataField="harvestGrade" icon2="bi bi-trash3"
                                                                        dispatch2="deleteHarvestGrade" label2="Delete"
                                                                        data="{{ $record->id }}" />

                                                                </div>
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
                                <td colspan="2" class="text-center text-muted py-5">
                                    No harvest grade records found
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <livewire:module.post-harvest.harvest-grade-edit-modal-livewire>
</div>
