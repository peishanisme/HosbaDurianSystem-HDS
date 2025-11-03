<?php

namespace App\Livewire\Module\PostHarvest;

use App\Models\Tree;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\HarvestEvent;
use Livewire\Attributes\Title;
use App\DataTransferObject\FruitDTO;

#[Title('Harvest Events')]
class HarvestEventOverviewLivewire extends Component
{
    use SweetAlert;
    public HarvestEvent $harvestEvent;

    public $tree_id;
    public $harvested_date;
    public $grade;
    public $weight;

    #[On('close-event')]
    public function closeEvent(HarvestEvent $harvestEvent)
    {
        $this->harvestEvent = $harvestEvent;
        $this->alertConfirm('Are you sure you want to close this harvest event?', 'confirm-close');
    }

    #[On('confirm-close')]
    public function confirmClose()
    {
        try {
            $this->harvestEvent->end_date = now()->toDateString();
            $this->harvestEvent->save();
            $this->alertSuccess('Harvest event closed successfully.');
        } catch (\Exception $e) {
            $this->alertError('An error occurred while closing the harvest event: ' . $e->getMessage());
        }
    }

    #[On('reopen-event')]
    public function reopenEvent(HarvestEvent $harvestEvent)
    {
        $this->harvestEvent = $harvestEvent;
        $this->alertConfirm('Are you sure you want to reopen this harvest event?', 'confirm-reopen');
    }

    #[On('confirm-reopen')]
    public function confirmReopen()
    {
        // dd($this->harvestEvent->end_date);
        try {
            $this->harvestEvent->end_date = null;
            $this->harvestEvent->save();
            $this->alertSuccess('Harvest event reopened successfully.');
        } catch (\Exception $e) {
            $this->alertError('An error occurred while reopening the harvest event: ' . $e->getMessage());
        }
    }

     public function save()
    {
        // You can save to DB here, for now just example:
        // Harvest::create([...])

        $this->validate([
            'tree_id' => 'required',
            'harvested_date' => 'required|date',
            'grade' => 'required',
            'weight' => 'required|numeric|min:0',
        ]);

        $tree = Tree::find($this->tree_id);

        $fruitDTO = new FruitDTO(
            harvest_uuid: $this->harvestEvent->uuid,
            transaction_uuid: null,
            harvested_at: $this->harvested_date,
            is_spoiled: false,
            tree_uuid: $tree->uuid ?? null,
            weight: $this->weight,
            grade: $this->grade
        );

        app('App\Actions\FruitManagement\CreateFruitAction')->handle($fruitDTO);

        session()->flash('message', 'Harvest saved successfully!');
        
        // Reset form after save
        $this->reset(['tree_id', 'harvested_date', 'grade', 'weight']);
    }


    public function render()
    {
        $trees = Tree::orderBy('tree_tag')->get();
        return view('livewire.module.post-harvest.harvest-event-overview-livewire',compact('trees'));
    }
}
