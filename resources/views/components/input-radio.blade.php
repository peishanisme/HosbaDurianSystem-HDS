@props(['id', 'name', 'value', 'model', 'label'])

<label for="{{ $id }}"
    class="w-100 d-flex align-items-center gap-3 p-3 border border-dashed rounded cursor-pointer
           form-check-label text-gray-700 border-gray-400 "
    style="
        transition: all 0.2s;
    ">
    <input
        type="radio"
        class="form-check-input me-2"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        wire:model="{{ $model }}"
    />
    <span class="fw-semibold small">{{ $label }}</span>
</label>


