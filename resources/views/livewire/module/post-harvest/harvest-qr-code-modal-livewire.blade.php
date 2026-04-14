<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="d-flex justify-content-center">
        <div class="text-center">
            @if (!$tree)
                <div class="spinner-border mb-4" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            @else
                <div class="qr-code-container mb-5">
                    {!! QrCode::size(150)->generate(route('public.portal', $tree->uuid)) !!}
                </div>

                <div class="fs-4 fw-bold mb-2">{{ $tree->tree_tag }}</div>

                <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-4">

                        {{-- <!-- Decrease -->
                        <button wire:click="decrement" class="btn btn-light">
                            <i class="ki-duotone ki-minus fs-2"></i>
                        </button> --}}

                        <!-- Input -->
                        <input type="number" wire:model.live.debounce.300ms="quantity" min="1" class="form-control text-center"
                            style="width: 70px;" />

                        {{-- <!-- Increase -->
                        <button wire:click="increment" class="btn btn-light">
                            <i class="ki-duotone ki-plus fs-2"></i>
                        </button> --}}

                    </div>
                </div>
            @endif
        </div>

        @slot('footer')
            <div class="wire:ignore">
                <button type="button" class="btn btn-primary"
                    onclick="printFruitLabels(
    '{{ $treeUrl }}',
    '{{ $treeTag }}',
    {{ $quantity }}
)">
                    Print QR Codes
                </button>
            </div>

            <x-button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        @endslot
</x-modal-component>
