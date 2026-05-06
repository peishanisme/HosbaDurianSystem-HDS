<?php

namespace App\Livewire\Module\PostHarvest;

use App\Models\HarvestEvent;
use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;

class HarvestEventHarvestSummaryLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    public HarvestEvent $harvestEvent;
    public $expandedTrees = [];
    public $expandedDays = [];

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-harvest-event']);
    }

    public function getTreesProperty()
    {
        return $this->harvestEvent->treeHarvestSummary()->get();
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

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-event-harvest-summary-livewire')->title(__('messages.harvest_event_harvest_summary'));
    }
}
