<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Exception;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\Agrochemical;
use App\Livewire\Forms\AgrochemicalStockMovementForm;

class AgrochemicalUpdateStockModalLivewire extends Component
{
    use SweetAlert;

    public string $modalID = 'agrochemicalStockMovementModalLivewire', $modalTitle = 'Agrochemical Stock Details';
    public AgrochemicalStockMovementForm $form;
    public ?Agrochemical $agrochemical = null;

    public function mount(){
        $this->form->agrochemical = $this->agrochemical;
        $this->form->name = $this->agrochemical?->name;
        $this->form->agrochemical_uuid = $this->agrochemical?->uuid;
    }

    #[On('reset-stock')]
    public function resetInput()
    {
        // $this->form->resetValidation();
        // $this->form->reset();
    }

    public function create(): void
    {
        $validatedData = $this->form->validate();

        try {

            $this->form->create($validatedData);
            $this->alertSuccess('Inventory has been created successfully.', $this->modalID);
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function update(): void
    {
        $validatedData = $this->form->validate();

        try {

            $this->form->update($validatedData);
            $this->alertSuccess('Inventory has been updated successfully.', $this->modalID);
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-update-stock-modal-livewire');
    }
}
