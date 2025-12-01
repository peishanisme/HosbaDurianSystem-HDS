<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Disease;
use App\Models\Tree;
use Livewire\Component;
use App\Models\HealthRecord;
use Livewire\Attributes\Title;

#[Title('Tree Overview')]
class TreeDetailsLivewire extends Component
{
    public Tree $tree;

    public function loadGrowthLogData()
    {
        return $this->tree->growthLogs()->orderBy('created_at')->get(['created_at', 'height', 'diameter']);
    }

    public function loadHarvestGradeData()
    {
        $fruits = $this->tree->fruits()->orderBy('created_at')->get(['grade', 'is_spoiled']);
        $spoiledCount = $fruits->where('is_spoiled', true)->count();
        $gradeCounts = $fruits->where('is_spoiled', false)->groupBy('grade')->map->count();
        return [
            'A' => $gradeCounts->get('A', 0),
            'B' => $gradeCounts->get('B', 0),
            'C' => $gradeCounts->get('C', 0),
            'D' => $gradeCounts->get('D', 0),
            'Spoiled' => $spoiledCount,
        ];
    }

    public function loadTreeDiseaseData()
    {
        $diseaseNames = Disease::pluck('diseaseName');
        $diseaseCounts = $this->tree->healthRecords()
            ->join('diseases', 'health_records.disease_id', '=', 'diseases.id')
            ->select('diseases.diseaseName')
            ->selectRaw('COUNT(*) as total')
            ->where('health_records.tree_uuid', $this->tree->uuid)
            ->groupBy('diseases.diseaseName')
            ->pluck('total', 'diseaseName');

        $diseaseCounts = $diseaseNames->mapWithKeys(function ($name) use ($diseaseCounts) {
            return [$name => (int) $diseaseCounts->get($name, 0)];
        });

        return $diseaseCounts->toArray();
    }

    public function render()
    {
        return view('livewire.module.tree-management.tree-details-livewire', [
            'growthLogData' => $this->loadGrowthLogData(),
            'harvestGradeData' => $this->loadHarvestGradeData(),
            'treeDiseaseData' => $this->loadTreeDiseaseData(),
        ]);
    }
}
