<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;

class ThumbnailInput extends Component
{
    use WithFileUploads;

    public $thumbnail;
    public string|null $thumbnailError = null;

    #[On('edit-thumbnail')]
    public function editThumbnail($thumbnail): void
    {
        $this->thumbnail = $thumbnail;
        $this->dispatch('thumbnail-updated', $thumbnail);
    }

    public function updatedThumbnail(): void
    {
        try {
            $this->validate(['thumbnail' => 'image|mimes:png,jpg,jpeg']);

            $this->dispatch('thumbnail-updated',  $this->thumbnail->getFilename());

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
        $this->thumbnailError = null;
        $this->dispatch('thumbnail-updated', null);
    }

    public function render()
    {
        return view('livewire.components.thumbnail-input');
    }
}
