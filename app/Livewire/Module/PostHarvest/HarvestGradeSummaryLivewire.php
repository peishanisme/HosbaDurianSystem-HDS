<?php

namespace App\Livewire\Module\PostHarvest;

use App\Models\HarvestEvent;
use App\Models\HarvestGrade;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Harvest Grade Summary')]
class HarvestGradeSummaryLivewire extends Component
{
    use SweetAlert;
    public HarvestEvent $harvestEvent;
    public array $expandedDates = [];
    public HarvestGrade$harvestGrade;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function toggleDate($date)
    {
        if (in_array($date, $this->expandedDates)) {
            $this->expandedDates = array_diff($this->expandedDates, [$date]);
        } else {
            $this->expandedDates[] = $date;
        }
    }

    #[On('deleteHarvestGrade')]
    public function deleteHarvestRecord($harvestGrade)
    {
        $this->harvestGrade = $this->harvestEvent->harvestGrades()->findOrFail($harvestGrade);
        $this->alertConfirm('Are you sure you want to delete this harvest grade record?', 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->harvestGrade->delete();
        $this->alertSuccess('Harvest grade record deleted successfully');
    }
    
    public function render()
    {
        return view('livewire.module.post-harvest.harvest-grade-summary-livewire');
    }
}
