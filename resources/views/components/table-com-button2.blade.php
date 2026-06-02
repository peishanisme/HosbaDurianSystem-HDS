@props([
    'label1' => 'Edit',
    'label2' => null,
    'modal1' => null,
    'modal2' => null,
    'dispatch1' => null,
    'dispatch2' => null,
    'disabled1' => false,
    'disabled2' => false,
    'permission1' => null,
    'permission2' => null,
])

<div class="d-flex gap-3">
    @can($permission1)
    <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-2"
        @if ($disabled1) disabled @endif data-bs-toggle="modal" data-bs-target="#{{ $modal1 }}"
        wire:click="$dispatch('{{ $dispatch1 }}')">
        {{ $label1 }}
    </button>
    @endcan

    @can($permission2)
    <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-2"
        @if ($disabled2) disabled @endif
        @if($modal2) data-bs-toggle="modal" data-bs-target="#{{ $modal2 }}" @endif
        wire:click="$dispatch('{{ $dispatch2 }}')">
        {{ $label2 }}
    </button>
    @endcan

</div>
