<div class="card shadow-sm rounded">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
        <h5 class="mb-0">Tree Harvest Summary</h5>

        <div class="d-flex flex-wrap gap-2">
            <!-- Search -->
            <input wire:model.live="search" type="text" class="form-control form-control-sm"
                placeholder="Search by tree tag or species..." style="width: 220px;">

            <!-- Species Filter -->
            <select wire:model.live="filterSpecies" class="form-select form-select-sm" style="width: 180px;">
                <option value="">All Species</option>
                @foreach ($speciesList as $species)
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
                        <th class="ps-8">Tree Tag</th>
                        <th>Species</th>
                        <th>Planted Date</th>
                        <th>Total Fruit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trees as $tree)
                        <!-- Main Tree Row -->
                        <tr wire:key="tree-{{ $tree->uuid }}">
                            <td class="align-middle">
                                <button data-tree-toggle data-uuid="{{ $tree->uuid }}"
                                    class="btn btn-link p-0 d-inline-flex align-items-center ms-5" type="button">
                                    <span class="me-2 d-inline-block transition-transform duration-200"
                                        style="transform: rotate({{ in_array($tree->uuid, $expanded) ? '90deg' : '0deg' }});">
                                        ▶
                                    </span>
                                </button>
                                <a href="{{ route('tree.show', $tree->id) }}"
                                    class="text-dark text-hover-primary align-middle fw-bold">
                                    {{ $tree->tree_tag }}
                                </a>
                            </td>

                            <td class="align-middle">{{ $tree->species->name ?? '-' }}</td>
                            <td class="align-middle">
                                {{ $tree->planted_at ? \Carbon\Carbon::parse($tree->planted_at)->format('d M Y') : '-' }}
                            </td>
                            <td class="align-middle">{{ $tree->fruitCountInHarvest($harvestEvent->uuid) }}</td>
                        </tr>

                        <!-- Expanded Fruit Details -->
                        <tr wire:key="tree-details-{{ $tree->uuid }}">
                            <td colspan="4" class="p-0">
                                <div id="expandable-{{ $tree->uuid }}"
                                    class="expandable {{ $expandedTreeUuid === $tree->uuid ? 'open' : '' }}">

                                    <div class="inner">
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0">
                                                <thead class="table-light">
                                                    <tr class="small text-uppercase text-muted">
                                                        <th class="ps-4">Fruit Tag</th>
                                                        <th>Weight (kg)</th>
                                                        <th>Grade</th>
                                                        <th>Harvested Date</th>
                                                        <th>QR Code</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($tree->fruits as $fruit)
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
                                                            <td class="small">
                                                                <button wire:click="showQrCode('{{ $fruit->uuid }}')"
                                                                    class="btn btn-sm btn-light-primary">
                                                                    <i class="bi bi-qr-code"></i> View
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="ps-4 small text-muted">No fruits
                                                                for this tree.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No trees found.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div wire:ignore.self class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrCodeModalLabel">Fruit QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    @if ($qrCode)
                        {!! $qrCode !!}
                        <p class="mt-3 text-muted small">{{ $fruitUuid }}</p>
                    @else
                        <div class="text-muted small">No QR Code generated yet.</div>
                    @endif
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
