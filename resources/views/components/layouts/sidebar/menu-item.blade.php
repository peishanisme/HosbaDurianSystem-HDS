@props(['title', 'route'])

<div>
    <a href="{{ route($route) }}" class="menu-link menu-link {{ Route::is($route) ? ' active' : '' }}" wire:navigate>
        <span class="menu-icon">
            <i class="ki-duotone ki-element-11 fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        <span class="menu-title">{{ $title }}</span>
    </a>
</div>
