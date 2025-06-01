@props(['title','route'])

<a href="{{ route($route) }}" class="menu-link{{ request()->routeIs($route) ? ' active' : '' }}" wire:navigate>
    <span class="menu-bullet">
        <span class="bullet bullet-dot"></span>
    </span>
    <span class="menu-title">{{$title}}</span>
</a>
