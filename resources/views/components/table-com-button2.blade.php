@props([
    'permission1' => null,
    'dispatch1' => null,
    'target1' => null,
    'label1' => null,
    'redirectUrl' => null,
    'permission2' => null,
    'dispatch2' => null,
    'target2' => null,
    'label2' => null,
])

<div class="d-flex gap-3">
    {{-- @can($permission1)
    
    @endcan --}}
    <x-redirect-button :permission="$permission1" :dispatch="$dispatch1" :target="$target1" :label="$label1" :redirectUrl="$redirectUrl" />

    {{-- @can($permission2)
    <button type="button" class="btn btn-sm btn-light-primary d-flex align-items-center gap-2"
        @if ($disabled2) disabled @endif
        wire:click="$dispatch('{{ $dispatch2 }}', { {{ $dataField }}: {{ $data }} })">
        <i class="{{ $icon2 }}"></i>
        {{ $label2 }}
    </button>
    @endcan --}}
    <livewire:components.modal-button :permission="$permission2" :dispatch="$dispatch2" :target="$target2" :label="$label2"/>

</div>
