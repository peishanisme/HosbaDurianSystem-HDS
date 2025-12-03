<?php

namespace App\Livewire\Module;

use Carbon\Carbon;
use App\Models\Tree;
use App\Models\Fruit;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Title('Dashboard')]
class DashboardLivewire extends Component
{
    public function loadTotalTreeData()
    {
        $speciesData = Tree::select('species.name as species', DB::raw('COUNT(trees.id) as total'))
            ->join('species', 'trees.species_id', '=', 'species.id')
            ->groupBy('species.name')
            ->get();

        $chartData = $speciesData->map(function ($item) {
            return [
                'category' => $item->species,
                'value' => $item->total
            ];
        });

        return $chartData;
    }

    public function loadTotalHarvestData()
    {
        $harvestData = Fruit::join('harvest_events', 'fruits.harvest_uuid', '=', 'harvest_events.uuid')
            ->select(
                'harvest_events.uuid as harvest_uuid',
                'harvest_events.event_name',
                DB::raw('COUNT(fruits.id) as total_fruits'),
                'harvest_events.created_at as harvested_at'
            )
            ->groupBy('harvest_events.uuid', 'harvest_events.event_name', 'harvest_events.created_at')
            ->orderBy('harvested_at', 'asc') 
            ->get();

        $chartData = $harvestData->map(function ($item) {
            return [
                'event' => $item->event_name,
                'total' => $item->total_fruits,
                'harvested_at' => $item->harvestEvent->start_date,
            ];
        });

        return ($chartData->toArray());
    }

    public function render()
    {
        return view(
            'livewire.dashboard-livewire',
            [
                'totalTreeData' => $this->loadTotalTreeData(),
                'totalHarvestedFruitsData' => $this->loadTotalHarvestData(),
            ]
        );
    }
}
