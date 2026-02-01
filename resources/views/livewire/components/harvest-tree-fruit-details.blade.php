<div class="card shadow-sm rounded">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('messages.tree_harvest_summary') }}</h5>

        <div class="d-flex flex-wrap gap-2">
            <!-- Search -->
            <input wire:model.live="search" type="text" class="form-control form-control-sm"
                placeholder="{{ __('messages.search_by_tree_tag_or_species') }}" style="width: 220px;">

            <!-- Species Filter -->
            <select wire:model.live="filterSpecies" class="form-select form-select-sm" style="width: 180px;">
                <option value="">{{ __('messages.all_species') }}</option>
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
                        <th class="ps-8">{{ __('messages.tree_tag') }}</th>
                        <th>{{ __('messages.species') }}</th>
                        <th>{{ __('messages.planted_date') }}</th>
                        <th>{{ __('messages.total_fruit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trees as $tree)
                        <!-- Main Tree Row -->
                        <tr wire:key="tree-{{ $tree->uuid }}">
                            <td class="align-middle py-5">
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

                            <td class="align-middle py-5">{{ $tree->species->name ?? '-' }}</td>
                            <td class="align-middle py-5">
                                {{ $tree->planted_at ? \Carbon\Carbon::parse($tree->planted_at)->format('d M Y') : '-' }}
                            </td>
                            <td class="align-middle py-5">{{ $tree->fruitCountInHarvest($harvestEvent->uuid) }}</td>
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
                                                        <th class="ps-4">{{ __('messages.fruit_tag') }}</th>
                                                        <th>{{ __('messages.weight') }}kg</th>
                                                        <th>{{ __('messages.grade') }}</th>
                                                        <th>{{ __('messages.harvested_date') }}</th>
                                                        <th>{{ __('messages.sell_status') }}</th>
                                                        <th>{{ __('messages.qr_code') }}</th>
                                                        <th>{{ __('messages.view_feedback') }}</th>
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
                                                                <span class="{{ $badgeClass }}">
                                                                    {{ __('messages.selling_status.' . $status) }}
                                                                </span>
                                                            </td>
                                                            <td class="small">
                                                                <button wire:click="showQrCode('{{ $fruit->uuid }}')"
                                                                    class="btn btn-sm btn-light-primary">
                                                                    <i class="bi bi-qr-code"></i>
                                                                    {{ __('messages.view_qr_code') }}
                                                                </button>
                                                            </td>
                                                            <td class="small">
                                                                <button
                                                                    wire:click="showFeedback('{{ $fruit->uuid }}')"
                                                                    class="btn btn-sm btn-light-primary">
                                                                    <i class="bi bi-eye"></i>
                                                                    {{ __('messages.view_feedback') }}
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="ps-4 small text-muted">
                                                                {{ __('messages.no_fruits_for_this_tree') }}</td>
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
                            <td colspan="4" class="text-center text-muted py-4">{{ __('messages.no_trees_found') }}
                            </td>
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
                    <h5 class="modal-title" id="qrCodeModalLabel">{{ __('messages.fruit_qr_code') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                            , 'fruit-{{ $fruitTag }}.png' )">{{ __('messages.download') }}</button>
                            <button class="btn btn-secondary"
                                onclick="printQR('Fruit- {{ $fruitTag }}')">{{ __('messages.print') }}</button>
                        </div>
                    @else
                        <div class="text-muted small">{{ __('messages.no_qr_code_generated_yet') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div wire:ignore.self class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">{{ __('messages.fruit_feedback') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <div class="text-muted my-4">{{ __('messages.no_feedback_available_for_this_fruit') }}</div>
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
