@props(['permission' => null, 'dispatch' => null, 'target', 'label'])

@can($permission)
    <button 
        type="button" 
        class="btn btn-primary btn-sm py-3"
        {{-- wire:click="$dispatch('{{ $dispatch }}')"  --}}
        data-bs-toggle="modal" 
        data-bs-target="#{{ $target }}">
        {{ $label }}
    </button>
@endcan
