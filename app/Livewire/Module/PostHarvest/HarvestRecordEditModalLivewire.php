<?php

namespace App\Livewire\Module\PostHarvest;

use App\Livewire\Forms\HarvestRecordForm;
use App\Models\HarvestRecord;
use App\Models\Tree;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class HarvestRecordEditModalLivewire extends Component
{
    use SweetAlert;
    public string $modalID = 'harvestEditModal', $modalTitle = 'Edit Harvest Record';
    public  $harvestRecord;
    public HarvestRecordForm $form;
    public $treeOptions = [];

    public function mount(): void
    {
        $this->treeOptions = Tree::pluck('tree_tag', 'uuid')->toArray();
        // $this->form->edit($this->harvestRecord);
    }

    #[On('edit-harvest-record')]
    public function openEditModal(HarvestRecord $harvestRecord)
    {
        $this->harvestRecord = $harvestRecord;
        $this->form->edit($harvestRecord);
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

            $this->form->update($this->harvestRecord);
            $this->alertSuccess('Harvest record updated successfully!',$this->modalID);

        } catch (\Exception $e) {

            $this->alertError('Failed to update harvest record: ' . $e->getMessage(), $this->modalID);

        }
    }

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-record-edit-modal-livewire');
    }
}
