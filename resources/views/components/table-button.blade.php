@props([
    'icon' => 'bi-pencil-square', 
    'label' => 'Edit',
    'modal' => null,
    'dispatch',
    'data',
    'dataField',
    'disabled' => false,
    'permission' => null
])

{{-- @can($permission) --}}
    <button 
        type="button"
        class="btn btn-sm btn-light-primary d-flex align-items-center gap-2"
        @if($disabled) disabled @endif
        data-bs-toggle="modal"
        data-bs-target="#{{ $modal }}"
        wire:click="$dispatch('{{ $dispatch }}', { {{ $dataField }}: {{ $data }} })"
    >
        <i class="{{ $icon }}"></i>
        {{ $label }}
    </button>
{{-- @endcan --}}
