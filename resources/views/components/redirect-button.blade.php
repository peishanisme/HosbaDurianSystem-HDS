@props([
    'permission' => null,
    'dispatch' => null,
    'target' => null,
    'label',
    'redirectUrl' => null
])

@can($permission)
    <button 
        type="button" 
        class="btn btn-primary btn-sm py-3"
        @if ($redirectUrl)
            onclick="window.location.href='{{ route($redirectUrl) }}'"
        @else
            @if ($dispatch)
                wire:click="$dispatch('{{ $dispatch }}')"
            @endif
            @if ($target)
                data-bs-toggle="modal" 
                data-bs-target="#{{ $target }}"
            @endif
        @endif
    >
        {{ $label }}
    </button>
@endcan
