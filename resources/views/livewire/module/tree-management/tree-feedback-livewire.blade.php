<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.tree-details-header :tree="$tree" />

    @if ($feedbacks->isEmpty())
        <span class="text-muted">
            No feedback available for this tree.
        </span>
    @else
        <div class="row" wire:ignore>
            @foreach ($feedbacks as $feedback)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div x-data="{ expanded: false, isOverflowing: false }" x-init="$nextTick(() => {
                        const el = $refs.content;
                        isOverflowing = el.scrollHeight > el.clientHeight;
                    })" class="card h-100 shadow-sm">
                        <div class="card-body">

                            <h6 class="card-title">
                                {{ optional($feedback->created_at)->format('M j, Y') ?? 'Unknown Date' }}
                            </h6>

                            <!-- Content -->
                            <p x-ref="content" class="card-text mb-2" :class="expanded ? '' : 'text-truncate-3'">
                                {{ $feedback->feedback }}
                            </p>

                            <!-- Toggle (ONLY if overflowing) -->
                            <button x-show="isOverflowing" @click="expanded = !expanded" class="btn btn-link p-0">
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
    </style>
@endpush
