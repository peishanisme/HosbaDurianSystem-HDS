<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Disease;
use Livewire\Component;

class TreeDiseaseDetailsModalLivewire extends Component
{
    public string $modalID = 'diseaseDetailsModalLivewire', $modalTitle = 'Disease Details';

    public ?Disease $disease = null;
    public $statusFilter = '';
    public $sortField = 'status';
    public $sortDirection = 'asc';
    public $trees = [];

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

    private function loadTrees()
    {
        if ($this->disease) {
            $query = $this->disease->trees();

            // ✅ Apply status filter
            if (!empty($this->statusFilter) && in_array($this->statusFilter, ['Severe', 'Medium', 'Recovered'])) {
                $query->wherePivot('status', '=', $this->statusFilter);
            }

            // ✅ Custom order for status (Severe → Medium → Recovered)
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
                // Normal sorting for other pivot fields
                $allowedSortFields = ['recorded_at', 'treatment'];
                if (in_array($this->sortField, $allowedSortFields)) {
                    $query->orderBy("health_records.{$this->sortField}", $this->sortDirection);
                }
            }

            $this->trees = $query->get();
        } else {
            $this->trees = collect();
        }
    }


    public function resetInput()
    {
        $this->disease = null;
        $this->trees = collect();
    }

    public function render()
    {
        return view('livewire.module.tree-management.tree-disease-details-modal-livewire');
    }
}
