<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use Livewire\Component;
use App\Models\HarvestEvent;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Title('Tree Harvest Record')]
class TreeHarvestRecordLivewire extends Component
{
    public Tree $tree;
    public $search = '';
    public $filterYear = '';
    public $expanded = [];

    public function toggleExpand($harvestUuid)
    {
        if (in_array($harvestUuid, $this->expanded)) {
            $this->expanded = array_diff($this->expanded, [$harvestUuid]);
        } else {
            $this->expanded[] = $harvestUuid;
        }
    }

    public function getHarvestsProperty()
    {
        return HarvestEvent::query()
            ->whereHas('fruits', fn($q) => $q->where('tree_uuid', $this->tree->uuid))
            ->with(['fruits' => fn($q) => $q->where('tree_uuid', $this->tree->uuid)])
            ->when($this->search, fn($q) => $q->where('event_name', 'like', "%{$this->search}%"))
            ->when($this->filterYear, fn($q) => $q->whereYear('start_date', $this->filterYear))
            ->orderByDesc('start_date')
            ->get();
    }
    public function render()
    {
        $years = DB::table('harvest_events')
            ->selectRaw('DISTINCT DATE_PART(\'year\', start_date) AS year')
            ->whereNull('deleted_at')
            ->orderByDesc('year')
            ->get();

        return view('livewire.module.tree-management.tree-harvest-record-livewire', [
            'harvests' => $this->harvests,
            'years' => $years,
        ]);
    }
}
