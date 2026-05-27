<?php

namespace App\Livewire\Module\PostHarvest;

use App\Models\HarvestEvent;
use App\Models\HarvestRecord;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class HarvestEventHarvestSummaryLivewire extends Component
{
    use AuthorizesRoleOrPermission, SweetAlert;
    public HarvestEvent $harvestEvent;
    public $expandedTrees = [];
    public $expandedDays = [];
    public string $search = '';
    public HarvestRecord $harvestRecord;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-harvest-event']);
    }

    public function getTreesProperty()
    {
        $query = $this->harvestEvent->treeHarvestSummary();

        if (!empty($this->search)) {
            $query->where('tree_tag', 'like', "%{$this->search}%");
        }

        return $query->get();
    }

    public function toggleExpand($treeUuid)
    {
        if (in_array($treeUuid, $this->expandedTrees)) {
            $this->expandedTrees = array_values(
                array_diff($this->expandedTrees, [$treeUuid])
            );
            return;
        }

        $this->expandedTrees[] = $treeUuid;
    }

    public function toggleDay($treeUuid, $date)
    {
        $key = $treeUuid . '_' . $date;

        if (in_array($key, $this->expandedDays)) {
            $this->expandedDays = array_values(
                array_diff($this->expandedDays, [$key])
            );
        } else {
            $this->expandedDays[] = $key;
        }
    }

    public function getRecordsByDay($treeUuid, $date)
    {
        return $this->harvestEvent->harvestRecords()
            ->where('tree_uuid', $treeUuid)
            ->whereDate('harvest_date', $date)
            ->get();
    }

    #[On('deleteHarvestRecord')]
    public function deleteHarvestRecord($harvestRecord)
    {
        $this->harvestRecord = $this->harvestEvent->harvestRecords()->findOrFail($harvestRecord);
        $this->alertConfirm('Are you sure you want to delete this harvest record?', 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->harvestRecord->delete();
        $this->alertSuccess('Harvest record deleted successfully');
    }

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-event-harvest-summary-livewire')->title(__('messages.harvest_event_harvest_summary'));
    }
}
