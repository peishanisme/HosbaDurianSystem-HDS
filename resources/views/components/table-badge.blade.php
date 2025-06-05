@props(['badge', 'label'])

<span class="badge rounded-pill {{ $badge }} py-2 px-5">
    <span class="rounded-circle {{ $badge }} d-inline-block" ></span>
    {{ $label }}
</span>
