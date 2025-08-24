
@props([
    'title',
    'icon' => null,
    'svg' => null,
])

<div data-kt-menu-trigger="click" {{ $attributes->merge(['class' => 'menu-item','menu-accordian']) }}>
    <span class="menu-link">
        <span class="menu-icon">
            @if ($svg)
                {!! $svg !!}
            @elseif ($icon)
                <i class="{{ $icon }} fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            @endif
        </span>
        <span class="menu-title">{{ $title }}</span>
        <span class="menu-arrow"></span>
    </span>

    <div class="menu-sub menu-sub-accordion">
        {{ $slot }}
    </div>
</div>

