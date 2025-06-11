@props(['button', 'content'])

<div x-data="{ open: false }" class="position-relative d-inline-block" wire:ignore.self>
    <!-- Trigger Button -->
    <button @click="open = !open" @click.outside="open = false" type="button" class="btn btn-light border">
        <i class="ki-outline ki-information"></i> {{ $button }}
    </button>

    <!-- Dropdown content -->
    <div
        x-show="open"
        x-transition
        class="position-absolute bg-white border rounded shadow p-3 mt-2"
        style="z-index: 1050; left: 0; min-width: 300px; max-width: 400px; white-space: normal; word-wrap: break-word;"
    >
        <pre class="mb-0 text-wrap small" style="white-space: pre-wrap; word-break: break-word;">
            {!! nl2br(e(json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) !!}
        </pre>
    </div>
</div>
