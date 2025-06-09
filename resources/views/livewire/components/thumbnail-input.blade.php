<div>
    {{-- Upload Preview Container --}}
    <div class="position-relative border rounded overflow-hidden d-flex align-items-center justify-content-center"
        style="width: 12rem; height: 12rem;">
        @if ($thumbnail)
            {{-- Image Preview --}}
            <img src="{{ is_string($thumbnail) ? secure_asset('storage/' . $thumbnail) : $thumbnail->temporaryUrl() }}"
                alt="Thumbnail Preview" class="w-100 h-100 object-fit-cover">

            {{-- Delete Icon --}}

            <button type="button"
                class="position-absolute bottom-0 end-0 m-1 d-flex align-items-center justify-content-center bg-danger text-white border-0 rounded-circle"
                style="width: 32px; height: 32px;" wire:click="removeThumbnail">
                <i class="bi bi-x-lg text-white"></i>
            </button>
        @else
            {{-- Placeholder --}}
            <img src="{{ secure_asset('assets/media/placeholder/placeholder.svg') }}" alt="Placeholder"
                class="w-100 h-100 object-fit-cover">
        @endif

        {{-- Edit Icon --}}
        <button type="button" onclick="document.getElementById('thumbnailInput').click()"
            class="position-absolute top-0 end-0 m-1 d-flex align-items-center justify-content-center p-0"
            style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background-color: #f8f9fa; border: none;">
            <i class="bi bi-pencil" style="font-size: 1rem; line-height: 1; color: #1e1e2d;"></i>
        </button>

    </div>

    {{-- File Input --}}
    <input type="file" accept="image/*" wire:model="thumbnail" class="d-none" id="thumbnailInput">

    {{-- Allowed file types --}}
    <small class="text-muted d-block mt-2">Allowed file types: png, jpg, jpeg.</small>

    {{-- Validation Error --}}
    @if ($thumbnailError)
        <br>
        <span class="text-danger text-2xs mt-1">
            {{ $thumbnailError }}
        </span>
    @endif

</div>
