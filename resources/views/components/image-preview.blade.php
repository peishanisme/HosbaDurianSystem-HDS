@php
    $symbolClass = "symbol symbol-{$thumbSize}px position-relative cursor-pointer";
@endphp

<div {{ $attributes->merge(['class' => $symbolClass]) }}
     data-bs-toggle="modal"
     data-bs-target="#{{ $modalId }}">

    <div class="symbol-label">
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            class="w-100 h-100 object-fit-cover rounded"
        />
    </div>

    <div class="position-absolute top-50 start-50 translate-middle
                p-3">
        <i class="ki-duotone ki-magnifier fs-2 text-white"></i>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 shadow-none">

            <div class="modal-body position-relative text-center p-4">

                <button type="button"
                    class="btn btn-icon btn-light position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </button>

                <img
                    src="{{ $src }}"
                    alt="{{ $alt }}"
                    class="rounded "
                    style="
                        max-width: {{ $maxSize }}px;
                        max-height: {{ $maxSize }}px;
                        width: 100%;
                        height: auto;
                        object-fit: contain;
                    "
                />

            </div>
        </div>
    </div>
</div>
