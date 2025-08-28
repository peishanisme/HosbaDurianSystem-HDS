@props(['route' => null, 'title', 'active' => false])

<li class="nav-item mt-2">
    <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ $active ? 'active' : '' }}" href="{{ $route }}">{{ $title }}</a>
</li>
