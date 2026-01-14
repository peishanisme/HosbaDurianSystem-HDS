<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Services\MediaService;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ThumbnailInput extends Component
{
    use WithFileUploads;

    public $thumbnail;
    public string|null $thumbnailError = null;
    public ?string $thumbnailPreviewUrl = null;


    #[On('edit-thumbnail')]
    public function editThumbnail($thumbnail): void
    {
        $this->thumbnail = $thumbnail;

        $this->thumbnailPreviewUrl = $this->thumbnail ? app(MediaService::class)->get($thumbnail) : null;

        $this->dispatch('thumbnail-updated', $thumbnail);
    }

    public function updatedThumbnail(): void
    {
        try {
            $this->validate([
                'thumbnail' => 'image|mimes:png,jpg,jpeg'
            ]);

            if ($this->thumbnail instanceof TemporaryUploadedFile) {
                $this->thumbnailPreviewUrl = $this->thumbnail->temporaryUrl();
            }

            $this->dispatch('thumbnail-updated', $this->thumbnail->getFilename());

            $this->thumbnailError = null;
        } catch (ValidationException $e) {
            $this->thumbnailError = $e->validator->errors()->first('thumbnail');
            $this->dispatch('thumbnail-validation-failed');
        }
    }

    #[On('reset-thumbnail')]
    public function removeThumbnail(): void
    {
        $this->thumbnail = null;
        $this->thumbnailPreviewUrl = null;
        $this->thumbnailError = null;
        $this->dispatch('thumbnail-updated', null);
    }

    public function render()
    {
        return view('livewire.components.thumbnail-input');
    }
}
