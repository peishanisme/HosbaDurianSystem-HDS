@props([
    'options' => [],
    'placeholder' => 'Select an option',
    'value' => null,
    'id' => null,
    'disabled' => false,
])

<select
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'form-control form-control-solid']) }}
    {{ $disabled ? 'disabled' : '' }}
>
    <option value="" @if($value === null) selected @endif>{{ $placeholder }}</option>

    @foreach ($options as $key => $option)
        <option value="{{ $key }}" @if($key == $value) selected @endif>{{ $option }}</option>
    @endforeach
</select>
