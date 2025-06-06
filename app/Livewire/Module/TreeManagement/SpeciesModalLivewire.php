<?php

namespace App\Livewire\Module\TreeManagement;

use Exception;
use App\Models\Species;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Livewire\Forms\SpeciesForm;

class SpeciesModalLivewire extends Component
{
    use SweetAlert;
    public SpeciesForm $form;

    public string $modalID = 'speciesModalLivewire', $modalTitle = 'Species Details';

    #[On('reset-species')]
    public function resetInput()
    {
        $this->form->resetValidation();
        $this->form->reset();
    }

    #[On('edit-species')]
    public function edit(Species $species): void
    {
        $this->resetInput();
        $this->form->edit($species);
    }

    public function create(): void
    {
        $validatedData = $this->form->validate();
        
        try {

            $this->form->create($validatedData);
            $this->alertSuccess('Species has been created successfully.', $this->modalID);

        
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        
        }
    }

    public function update(): void
    {
        $validatedData = $this->form->validate();
        
        try {

            $this->form->update($validatedData);
            $this->alertSuccess('Species has been updated successfully.', $this->modalID);

        
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        
        }
    }

    public function render()
    {
        return view('livewire.tree-management.species-modal-livewire');
    }
}
