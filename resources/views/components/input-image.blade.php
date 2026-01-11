@props(['model', 'size' => 125, 'existing' => null])

<div>
    <div class="image-input {{ $existing ? '' : 'image-input-empty' }}" data-kt-image-input="true">
        <!-- Preview -->
        <div class="image-input-wrapper"
            style="
        width: {{ $size }}px;
        height: {{ $size }}px;
        background-image: url('{{ $existing ? app(\App\Services\MediaService::class)->get($existing) : asset('assets/media/placeholder/placeholder.svg') }}');
        background-size: cover;
        background-position: center;
    ">
        </div>
        
        <!-- CHANGE -->
        <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change image">
            <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

            <input type="file" wire:model="{{ $model }}" accept=".png,.jpg,.jpeg" />
            <input type="hidden" name="avatar_remove" />
        </label>

        <!-- CANCEL -->
        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel" wire:click="resetImage">
            <i class="ki-outline ki-cross fs-3"></i>
        </span>

        <!-- REMOVE -->
        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove" wire:click="resetImage">
            <i class="ki-outline ki-cross fs-3"></i>
        </span>
    </div>
</div>


