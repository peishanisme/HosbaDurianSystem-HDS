@props(['id', 'title', 'footer'])

<div wire:ignore.self class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h4 class="modal-title" id="{{ $id }}Label">{{ $title }}</h4>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    wire:click='resetInput'
                    aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body">
                {{ $slot }}
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer">
                {{ $footer }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const modalEl = document.getElementById('{{ $id }}');
        const bsModal = new bootstrap.Modal(modalEl);

        modalEl.addEventListener('hidden.bs.modal', function() {
            Livewire.dispatch('reset-form');
        });
    </script>
@endpush
