<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.tree-details-header :tree="$tree" />

    @if ($feedbacks->isEmpty())
        <span class="text-muted">
            No feedback available for this tree.
        </span>
    @else
        <div class="d-flex justify-content-end mb-3">
            <button wire:click="sortByDate"
                class="btn btn-sm btn-light d-flex align-items-center gap-1 {{ $sortDirection === 'desc' ? 'active' : '' }}">

                <span>Sort by Date</span>

                <!-- Arrow -->
                @if ($sortDirection === 'asc')
                    <i class="bi bi-arrow-up"></i>
                @else
                    <i class="bi bi-arrow-down"></i>
                @endif

            </button>
        </div>

        <div class="row align-items-stretch">
            @foreach ($feedbacks as $feedback)
                <div class="col-md-6 col-lg-4 mb-4" wire:key="feedback-{{ $feedback->id }}">
                    <div x-data="{ expanded: false, isOverflowing: false }" x-init="$nextTick(() => {
                        setTimeout(() => {
                            const el = $refs.content;
                            isOverflowing = el.scrollHeight > el.clientHeight;
                        }, 50);
                    })"
                        x-effect="
    const el = $refs.content;
    if (el) {
        isOverflowing = el.scrollHeight > el.clientHeight;
    }
"
                        class="card shadow-sm h-100 d-flex flex-column feedback-card">
                        <div class="card-body">

                            <h6 class="card-title">
                                {{ optional($feedback->created_at)->format('M j, Y') ?? 'Unknown Date' }}
                            </h6>

                            <!-- Content -->
                            <p x-ref="content" class="card-text mb-2" :class="expanded ? '' : 'text-truncate-3'">
                                {{ $feedback->feedback }}
                            </p>

                            <!-- Toggle (ONLY if overflowing) -->
                            <button x-show="isOverflowing" @click="expanded = !expanded"
                                class="btn btn-link p-0 text-primary">
                                <span x-text="expanded ? 'Show less' : 'Show more'"></span>
                            </button>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <livewire:module.post-harvest.harvest-qr-code-modal-livewire />
</div>

@push('styles')
    <style>
        .text-truncate-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .feedback-card {
            transition: max-height 0.3s ease;
            min-height: 150px;
        }
    </style>
@endpush
