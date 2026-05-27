<?php

namespace App\Livewire\Module\PostHarvest;

use App\Enum\TransactionGrade;
use App\Livewire\Forms\HarvestGradeForm;
use App\Models\HarvestGrade;
use App\Models\Species;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class HarvestGradeEditModalLivewire extends Component
{
    use SweetAlert;
    public string $modalID = 'harvestGradeEditModal', $modalTitle = 'Edit Harvest Grade';
    public HarvestGradeForm $form;
    public  $harvestGrade;
    public $speciesOptions = [], $gradeOptions = [];

    public function mount(): void
    {
        $this->speciesOptions = Species::pluck('name', 'id')->toArray();
        $this->gradeOptions = collect(TransactionGrade::cases())
            ->mapWithKeys(fn($grade) => [
                $grade->value => $grade->value
            ])
            ->toArray();
    }

    #[On('editHarvestGrade')]
    public function openEditModal(HarvestGrade $harvestGrade)
    {
        $this->harvestGrade = $harvestGrade;
        $this->form->edit($harvestGrade);
    }

    public function resetInput()
    {
        $this->resetValidation();
        $this->form->reset();
    }

    public function update()
    {
        $this->form->validate();

        try {

            $this->form->update($this->harvestGrade);
            $this->alertSuccess('Harvest grade record updated successfully!', $this->modalID);
        } catch (\Exception $e) {

            $this->alertError('Failed to update harvest grade record: ' . $e->getMessage(), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-grade-edit-modal-livewire');
    }
}
