@props(['disabled' => false])

<button type="submit" {{ $attributes->merge(['class' => 'btn']) }} {{ $disabled ? 'disabled' : '' }}>
    {{ $slot }}
</button>
