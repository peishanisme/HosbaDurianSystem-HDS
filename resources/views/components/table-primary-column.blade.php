@props([
    'title' => null,
    'subtitle' => null,
    'thumbnail' => null,
    'route' => null,
    'bracket' => null,
    'avatar' => null,
])

<div class="d-flex align-items-center">
    {{-- @if ($thumbnail && $thumbnail != 'default')
        <div class="me-3">
            <img class="rounded object-fit-cover" style="width: 60px; aspect-ratio: 1/1;" src="{{ secure_asset('storage/' . $thumbnail) }}" alt="Image">
        </div>
    @elseif ($thumbnail && $thumbnail == 'default')
        <div class="me-3">
            <img class="rounded object-fit-cover" style="width: 60px; aspect-ratio: 1/1;" src="{{ secure_asset('assets/media/placeholder/placeholder.svg') }}" alt="Placeholder">
        </div>
    @endif --}}
    @if ($avatar == null && $thumbnail)

        <div class="me-3">
            @php
                $imageUrl =
                    $thumbnail && $thumbnail !== 'default'
                        ? app(\App\Services\MediaService::class)->get($thumbnail)
                        : app(\App\Services\MediaService::class)->get('logo/placeholder.svg');

                $usePreview = $thumbnail && $thumbnail !== 'default';
            @endphp

            @if ($usePreview)
                <x-image-preview :src="$imageUrl" alt="Image" thumb-size="60" max-size="400"
                    modal-id="imagePreview-{{ $loop->index ?? uniqid() }}" />
            @else
                <img src="{{ $imageUrl }}" alt="Placeholder" class="rounded object-fit-cover"
                    style="width: 60px; aspect-ratio: 1/1;" />
            @endif
        </div>
    @endif

    @if ($avatar != null)
        <div class="me-3">
            <x-avatar :name="$avatar" size="40px" />
        </div>
    @endif

    <div class="d-flex flex-column gap-1">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ $route }}" class="text-dark text-decoration-none fw-semibold text-hover-primary">
                {{ $title }}
            </a>
            @if ($bracket)
                <span class="text-muted">({{ $bracket }})</span>
            @endif
        </div>
        @if ($subtitle)
            <span class="text-muted small">{{ $subtitle }}</span>
        @endif
    </div>
</div>
