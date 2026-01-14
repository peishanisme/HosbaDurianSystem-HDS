<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Disease;
use Livewire\Component;

class DiseaseDetailsModalLivewire extends Component
{
    public string $modalID = 'diseaseDetailsModalLivewire', $modalTitle = 'Disease Details';

    public ?Disease $disease = null;
    public $statusFilter = '';
    public $sortField = 'status';
    public $sortDirection = 'asc';
    public $trees = [];
    public $noTree = false;

    protected $listeners = ['view-disease' => 'loadDisease'];

    public function loadDisease(Disease $disease)
    {
        $this->disease = $disease;
        $this->loadTrees();
    }

    public function updatedStatusFilter()
    {
        $this->loadTrees();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadTrees();
    }

    public function loadTrees()
    {
        if (!$this->disease) {
            $this->trees = collect();
            return;
        }

        $query = $this->disease->trees()
            ->wherePivot('recorded_at', function ($sub) {
                $sub->selectRaw('MAX(hr2.recorded_at)')
                    ->from('health_records as hr2')
                    ->whereColumn('hr2.tree_uuid', 'health_records.tree_uuid')
                    ->whereColumn('hr2.disease_id', 'health_records.disease_id');
            })
            ->wherePivot('created_at', function ($sub) {
                $sub->selectRaw('MAX(hr3.created_at)')
                    ->from('health_records as hr3')
                    ->whereColumn('hr3.tree_uuid', 'health_records.tree_uuid')
                    ->whereColumn('hr3.disease_id', 'health_records.disease_id')
                    ->whereColumn('hr3.recorded_at', 'health_records.recorded_at');
            });

        // Status filter
        if (!empty($this->statusFilter) && in_array($this->statusFilter, ['Severe', 'Medium', 'Recovered'])) {
            $query->wherePivot('status', $this->statusFilter);
        }

        // Sorting
        if ($this->sortField === 'status') {
            $query->orderByRaw("
            CASE health_records.status
                WHEN 'Severe' THEN 1
                WHEN 'Medium' THEN 2
                WHEN 'Recovered' THEN 3
                ELSE 4
            END {$this->sortDirection}
        ");
        } else {
            $allowedSortFields = ['recorded_at', 'treatment', 'created_at'];
            if (in_array($this->sortField, $allowedSortFields)) {
                $query->orderBy("health_records.{$this->sortField}", $this->sortDirection);
            }
        }

        $this->trees = $query->get();
        $this->noTree = $this->trees->isEmpty();
    }

    public function resetInput()
    {
        $this->disease = null;
        $this->noTree = false;
        $this->trees = collect();
    }

    public function render()
    {
        return view('livewire.module.tree-management.disease-details-modal-livewire');
    }
}
