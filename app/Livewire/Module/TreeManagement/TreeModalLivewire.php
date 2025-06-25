<?php

namespace App\Livewire\Module\TreeManagement;

use App\Livewire\Forms\TreeForm;
use Exception;
use App\Models\Tree;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;

class TreeModalLivewire extends Component
{
    use SweetAlert;
    public TreeForm $form;

    public string $modalID = 'treeModalLivewire', $modalTitle = 'Tree Details';
    public array $speciesOptions = [];
    public bool $isThumbnailValid = true;


    public function mount(): void
    {
        $this->speciesOptions = $this->form->getSpeciesOptions();
    }

    #[On('reset-tree')]
    public function resetInput()
    {
        $this->form->resetValidation();
        $this->form->reset();
        $this->dispatch('reset-thumbnail');
    }

    #[On('thumbnail-updated')]
    public function setThumbnail($thumbnail): void
    {
        $this->form->thumbnail = $thumbnail;
        $this->isThumbnailValid = true;
    }

    #[On('thumbnail-validation-failed')]
    public function handleThumbnailValidationError(): void
    {
        $this->isThumbnailValid = false;
    }

    #[On('edit-tree')]
    public function edit(Tree $tree): void
    {
        $this->resetInput();
        $this->form->edit($tree);
        $this->dispatch('edit-thumbnail', $tree->thumbnail);
    }

    public function create(): void
    {
        if (!$this->isThumbnailValid) {
            return;
        }

        $validatedData = $this->form->validate();

        try {

            $this->form->create($validatedData);
            $this->alertSuccess('Tree has been created successfully.', $this->modalID);
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
            $this->alertSuccess('Tree has been updated successfully.', $this->modalID);
            $this->resetInput();
            
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        }
    }
    public function render()
    {
        return view('livewire.module.tree-management.tree-modal-livewire');
    }
}
