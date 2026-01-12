<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Exception;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\Agrochemical;
use App\Enum\AgrochemicalType;
use Illuminate\Support\Facades\Log;
use App\Livewire\Forms\AgrochemicalForm;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AgrochemicalModalLivewire extends Component
{
    use SweetAlert;
    public AgrochemicalForm $form;
    public string $modalID = 'agrochemicalModalLivewire', $modalTitle = 'Agrochemical Inventory Details';
    public array $typeOptions = [];
    public bool $isThumbnailValid = true;

    public function mount()
    {
        $this->typeOptions = AgrochemicalType::keyValue();
    }

    #[On('reset-agrochemical')]
    public function resetInput()
    {
        $this->form->resetValidation();
        $this->form->reset();
        $this->dispatch('reset-thumbnail');
    }


    #[On('thumbnail-updated')]
    public function setThumbnail($path): void
    {
        if ($path) {
            $this->form->thumbnail = new TemporaryUploadedFile(
                storage_path('app/livewire-tmp/' . $path),
                'local'
            );
            $this->isThumbnailValid = true;
        } else {
            $this->form->thumbnail = null;
        }
    }

    #[On('thumbnail-validation-failed')]
    public function handleThumbnailValidationError(): void
    {
        $this->isThumbnailValid = false;
    }

    #[On('edit-agrochemical')]
    public function edit(Agrochemical $agrochemical): void
    {
        $this->resetInput();
        $this->form->edit($agrochemical);
        $this->dispatch('edit-thumbnail', $agrochemical->thumbnail);
    }

    public function create(): void
    {
        if (!$this->isThumbnailValid) {
            return;
        }

        $validatedData = $this->form->validate();

        try {

            $this->form->create($validatedData);
            $this->alertSuccess('Inventory has been created successfully.', $this->modalID);
            $this->resetInput();
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function update(): void
    {
        if (!$this->isThumbnailValid) {
            return;
        }

        $validatedData = $this->form->validate();

        try {

            $this->form->update($validatedData);
            $this->alertSuccess('Inventory has been updated successfully.', $this->modalID);
            $this->resetInput();
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-modal-livewire');
    }
}
