@props([
    'icon1' => 'bi-pencil-square',
    'icon2' => null,
    'label1' => 'Edit',
    'label2' => null,
    'modal' => null,
    'dispatch1' => null,
    'dispatch2' => null,
    'data',
    'dataField',
    'disabled1' => false,
    'disabled2' => false,
    'permission1' => null,
    'permission2' => null,
])

<div class="d-flex gap-3">
    @can($permission1)
    <button type="button" class="btn btn-sm btn-light-primary d-flex align-items-center gap-2"
        @if ($disabled1) disabled @endif data-bs-toggle="modal" data-bs-target="#{{ $modal }}"
        wire:click="$dispatch('{{ $dispatch1 }}', { {{ $dataField }}: {{ $data }} })">
        <i class="{{ $icon1 }}"></i>
        {{ $label1 }}
    </button>
    @endcan

    @can($permission2)
    <button type="button" class="btn btn-sm btn-light-primary d-flex align-items-center gap-2"
        @if ($disabled2) disabled @endif
        wire:click="$dispatch('{{ $dispatch2 }}', { {{ $dataField }}: {{ $data }} })">
        <i class="{{ $icon2 }}"></i>
        {{ $label2 }}
    </button>
    @endcan

</div>
