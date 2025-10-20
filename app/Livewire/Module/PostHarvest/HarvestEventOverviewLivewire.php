<?php

namespace App\Livewire\Module\PostHarvest;

use App\Traits\SweetAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\HarvestEvent;
use Livewire\Attributes\Title;

#[Title('Harvest Events')]
class HarvestEventOverviewLivewire extends Component
{
    use SweetAlert;
    public HarvestEvent $harvestEvent;

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


    public function render()
    {
        return view('livewire.module.post-harvest.harvest-event-overview-livewire');
    }
}
