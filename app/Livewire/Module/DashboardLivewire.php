<?php

namespace App\Livewire\Module;

use App\Models\Tree;
use App\Models\Fruit;
use Livewire\Component;
use App\Models\Transaction;
use App\Models\HealthRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\WeatherController;

class DashboardLivewire extends Component
{
    public $weather;
    public $totalTreeData;
    public $totalHarvestedFruitsData;
    public $totalTransactionData;
    public $topSellingSpecies;
    public $treeHealthRecords;

    public function mount()
    {
        $this->weather = Cache::remember(
            'weather',
            600,
            fn() => (new WeatherController)->getCurrentWeather()
        );

        $this->totalTreeData = $this->loadTotalTreeData();
        $this->totalHarvestedFruitsData = $this->loadTotalHarvestData();
        $this->totalTransactionData = $this->loadTotalTransactionData();
        $this->topSellingSpecies = $this->loadTopSellingSpecies();
        $this->treeHealthRecords = $this->getTreeHealthRecords();
    }

    public function getTreeHealthRecords()
    {
        $severeTrees = HealthRecord::select(
            'trees.id as treeId',
            'trees.tree_tag as treeTag',
            'diseases.diseaseName',
            'health_records.status',
            'health_records.updated_at'
        )
            ->join('trees', 'health_records.tree_uuid', '=', 'trees.uuid')
            ->join('diseases', 'health_records.disease_id', '=', 'diseases.id')
            ->whereIn('health_records.status', ['Severe', 'Medium'])
            ->orderByRaw("
            CASE
                WHEN health_records.status = 'Severe' THEN 0
                WHEN health_records.status = 'Medium' THEN 1
                ELSE 2
            END
            ")
            ->orderBy('health_records.updated_at', 'desc')
            ->get();

        return ($severeTrees);
    }

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

    public function loadTotalTransactionData()
    {
        return Transaction::selectRaw('DATE(date) as date, SUM(total_price) as total_price')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($item) => [
                'date' => $item->date,
                'value' => (float) $item->total_price,
            ])->toArray();
    }

    public function loadTopSellingSpecies()
    {
        $topSelling = Fruit::select('species.name as species', DB::raw('COUNT(*) as total'))
            ->join('trees', 'fruits.tree_uuid', '=', 'trees.uuid')
            ->join('species', 'trees.species_id', '=', 'species.id')
            ->whereNotNull('fruits.transaction_uuid')
            ->groupBy('species.name')
            ->orderByDesc('total')
            ->get();

        return $topSelling;
    }

    public function render()
    {
        return view(
            'livewire.dashboard-livewire'
        )->title(__('messages.dashboard'));
    }
}
