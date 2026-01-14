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
                                                        <th>Sell Status</th>
                                                        <th>QR Code</th>
                                                        <th>View Feedback</th>
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
                                                                        'AA' => 'badge bg-success-subtle text-success',
                                                                        'A' => 'badge bg-primary-subtle text-primary',
                                                                        'B' => 'badge bg-warning-subtle text-warning',
                                                                        'C' => 'badge bg-info-subtle text-info',
                                                                        'D' => 'badge bg-danger-subtle text-danger',
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
                                                            <td class="small">
                                                                @php
                                                                    $status = $fruit->transaction_uuid
                                                                        ? 'Sold'
                                                                        : 'Available';
                                                                    $gradeClassMap = [
                                                                        'Available' =>
                                                                            'badge bg-success-subtle text-success',
                                                                        'Sold' => 'badge bg-danger-subtle text-danger',
                                                                    ];
                                                                    $badgeClass =
                                                                        $gradeClassMap[$status] ??
                                                                        'badge bg-secondary-subtle text-secondary';
                                                                @endphp
                                                                <span
                                                                    class="{{ $badgeClass }}">{{ $status ?: '-' }}</span>
                                                            </td>
                                                            <td class="small">
                                                                <button wire:click="showQrCode('{{ $fruit->uuid }}')"
                                                                    class="btn btn-sm btn-light-primary">
                                                                    <i class="bi bi-qr-code"></i> View QR
                                                                </button>
                                                            </td>
                                                            <td class="small">
                                                                <button
                                                                    wire:click="showFeedback('{{ $fruit->uuid }}')"
                                                                    class="btn btn-sm btn-light-primary">
                                                                    <i class="bi bi-eye"></i> View
                                                                </button>
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
                <div wire:ignore.self class="modal fade" id="qrCodeModal" tabindex="-1"
                    aria-labelledby="qrCodeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-3">
                            <div class="modal-header">
                                <h5 class="modal-title" id="qrCodeModalLabel">Fruit QR Code</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center py-4">
                                @if ($qrCode)
                                    <div id="qrCodeWrapper">
                                        {!! $qrCode !!}
                                    </div>
                                    <p class="mt-3 text-muted small">{{ $fruitUuid }}</p>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary"
                                            onclick="downloadQR( 'qrCodeWrapper'
                                            , 'fruit-{{ $fruitTag }}.png' )">Download</button>
                                        <button class="btn btn-secondary"
                                            onclick="printQR('Fruit- {{ $fruitTag }}')">Print</button>
                                    </div>
                                @else
                                    <div class="text-muted small">No QR Code generated yet.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedback Modal -->
                <div wire:ignore.self class="modal fade" id="feedbackModal" tabindex="-1"
                    aria-labelledby="feedbackModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-3">
                            <div class="modal-header">
                                <h5 class="modal-title" id="feedbackModalLabel">Fruit Feedback</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center py-4">
                                @if ($feedback && $feedback->count() > 0)
                                    <div class="list-group">
                                        @foreach ($feedback as $fb)
                                            <div class="card mb-3 shadow-sm border-0">
                                                <div class="card-body d-flex flex-column">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0 fw-bold text-dark">{{ $fb->feedback }}</h6>
                                                        <span
                                                            class="text-muted small">{{ $fb->created_at->format('Y-m-d g:i A') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-muted my-4">No feedback available for this fruit.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        window.addEventListener('show-qr-modal', () => {
            const modal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
            modal.show();
        });
    </script>

    <script>
        window.addEventListener('show-feedback-modal', () => {
            const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            modal.show();
        });
    </script>

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
