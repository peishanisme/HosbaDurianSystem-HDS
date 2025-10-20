<?php

namespace App\Livewire\Components;

use App\Models\Tree;
use App\Models\Species;
use Livewire\Component;
use App\Models\HarvestEvent;

class HarvestTreeFruitDetails extends Component
{
    public $harvestEvent;      // Harvest Event model passed from parent
    public $search = '';       // For searching tree_tag or species
    public $filterSpecies = ''; // For dropdown filtering by species
    public $expanded = [];     // For toggle details

    protected $queryString = ['search', 'filterSpecies']; // Optional: keeps state in URL


    public function toggleExpand($uuid)
    {
        if (in_array($uuid, $this->expanded)) {
            $this->expanded = array_diff($this->expanded, [$uuid]);
        } else {
            $this->expanded[] = $uuid;
        }
    }

    public function updating($field)
    {
        if (in_array($field, ['search', 'filterSpecies'])) {
            $this->render();
        }
    }

    public function render()
    {
        $searchLower = mb_strtolower($this->search);

        $trees = Tree::with(['species', 'fruits' => function ($query) {
            $query->where('harvest_uuid', $this->harvestEvent->uuid);
            }])
            ->whereHas('fruits', function ($query) {
            $query->where('harvest_uuid', $this->harvestEvent->uuid);
            })
            ->when($this->search, function ($query) use ($searchLower) {
            $query->where(function ($subQuery) use ($searchLower) {
                $subQuery->whereRaw('LOWER(tree_tag) LIKE ?', ['%' . $searchLower . '%'])
                ->orWhereHas('species', function ($q) use ($searchLower) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchLower . '%']);
                });
            });
            })
            ->when($this->filterSpecies, function ($query) {
            $query->whereHas('species', function ($q) {
                $q->where('id', $this->filterSpecies);
            });
            })
            ->get();

        $speciesList = Species::orderBy('name')->get();

        return view('livewire.components.harvest-tree-fruit-details', [
            'trees' => $trees,
            'speciesList' => $speciesList,
        ]);
    }
}
