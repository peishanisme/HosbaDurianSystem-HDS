@props(['disabled' => false])

<input {!! $attributes->merge(['class' => 'form-control form-control-solid']) !!} {{ $disabled ? 'disabled' : '' }}>
