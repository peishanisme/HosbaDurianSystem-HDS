@props([
    'name' => 'User',
    'size' => '50px',
])

@php
    // Define possible color styles
    $styles = [
        ['bg-primary', 'text-inverse-primary'],
        ['bg-danger', 'text-inverse-danger'],
        ['bg-info', 'text-inverse-info'],
        ['text-success'],
        ['text-danger'],
        ['text-warning'],
    ];

    // Use name to generate consistent color (so same user gets same color)
    $index = crc32($name) % count($styles);
    $colorStyle = $styles[$index];

    // Get first capital letter
    $initial = strtoupper(substr(trim($name), 0, 1));

    // Combine the classes
    $classes = implode(' ', $colorStyle);
@endphp

<div class="symbol">
    <div class="symbol-label fw-semibold {{ $classes }}" style="width: {{ $size }}; height: {{ $size }}; font-size: calc({{ $size }} / 3);">
        {{ $initial }}
    </div>
</div>
