<?php

namespace App\Livewire\Module;

use App\Http\Controllers\WeatherController;
use App\Models\Fruit;
use App\Models\HarvestRecord;
use App\Models\HealthRecord;
use App\Models\Transaction;
use App\Models\Tree;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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
            'health_records.recorded_at'
        )
            ->join('trees', 'health_records.tree_uuid', '=', 'trees.uuid')
            ->join('diseases', 'health_records.disease_id', '=', 'diseases.id')

            // Latest recorded_at per tree + disease
            ->where('health_records.recorded_at', function ($sub) {
                $sub->selectRaw('MAX(hr2.recorded_at)')
                    ->from('health_records as hr2')
                    ->whereColumn('hr2.tree_uuid', 'health_records.tree_uuid')
                    ->whereColumn('hr2.disease_id', 'health_records.disease_id');
            })

            // Tie-breaker: latest created_at if recorded_at is the same
            ->where('health_records.created_at', function ($sub) {
                $sub->selectRaw('MAX(hr3.created_at)')
                    ->from('health_records as hr3')
                    ->whereColumn('hr3.tree_uuid', 'health_records.tree_uuid')
                    ->whereColumn('hr3.disease_id', 'health_records.disease_id')
                    ->whereColumn('hr3.recorded_at', 'health_records.recorded_at');
            })

            // Filter status
            ->whereIn('health_records.status', ['Severe', 'Medium'])

            // Severity priority
            ->orderByRaw("
            CASE
                WHEN health_records.status = 'Severe' THEN 0
                WHEN health_records.status = 'Medium' THEN 1
                ELSE 2
            END
        ")

            // Latest records first (recorded_at then created_at)
            ->orderBy('health_records.recorded_at', 'desc')
            ->orderBy('health_records.created_at', 'desc')

            ->get();

        return $severeTrees;
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
        $harvestData = HarvestRecord::join('harvest_events', 'harvest_records.harvest_uuid', '=', 'harvest_events.uuid')
            ->select(
                'harvest_events.uuid as harvest_uuid',
                'harvest_events.event_name',
                DB::raw('SUM(harvest_records.num_of_fruits) as total_fruits'),
                'harvest_events.start_date as harvested_at'
            )
            ->groupBy(
                'harvest_events.uuid',
                'harvest_events.event_name',
                'harvest_events.start_date'
            )
            ->orderBy('harvested_at', 'asc')
            ->get();

        $chartData = $harvestData->map(function ($item) {
            return [
                'event' => $item->event_name,
                'total' => $item->total_fruits,
                'harvested_at' => $item->harvested_at,
            ];
        });

        return $chartData->toArray();
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
