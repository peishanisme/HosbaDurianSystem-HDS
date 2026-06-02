<?php

namespace App\Livewire\Module\PostHarvest;

use App\Models\Fruit;
use App\Models\HarvestEvent;
use App\Models\HarvestGrade;
use App\Models\HarvestRecord;
use App\Models\Tree;
use App\Models\TreeObservation;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\SweetAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class HarvestEventOverviewLivewire extends Component
{
    use SweetAlert, AuthorizesRoleOrPermission;
    public HarvestEvent $harvestEvent;

    public $tree_id;
    public $harvested_date;
    public $grade;
    public $weight;
    public Fruit $fruit;
    public ?string $fromDate = null;
    public ?string $toDate = null;

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-harvest-event']);
    }

    #[On('close-event')]
    public function closeEvent(HarvestEvent $harvestEvent)
    {
        $this->harvestEvent = $harvestEvent;
        $this->alertConfirm(__('messages.are_you_sure_close'), 'confirm-close');
    }

    #[On('confirm-close')]
    public function confirmClose()
    {
        try {
            $this->harvestEvent->end_date = now()->toDateString();
            $this->harvestEvent->active = false;
            $this->harvestEvent->save();
            $this->alertSuccess(__('messages.harvest_event_closed_successfully'));
        } catch (\Exception $e) {
            $this->alertError(__('messages.error_occurred') . $e->getMessage());
        }
    }

    #[On('reopen-event')]
    public function reopenEvent(HarvestEvent $harvestEvent)
    {
        $this->harvestEvent = $harvestEvent;
        $this->alertConfirm(__('messages.are_you_sure_reopen'), 'confirm-reopen');
    }

    #[On('confirm-reopen')]
    public function confirmReopen()
    {
        try {
            $this->harvestEvent->end_date = null;
            $this->harvestEvent->active = true;
            $this->harvestEvent->save();
            $this->alertSuccess(__('messages.harvest_event_reopened_successfully'));
        } catch (\Exception $e) {
            $this->alertError(__('messages.error_occurred') . $e->getMessage());
        }
    }

    public function getTop10HarvestTreesDataProperty()
    {
        $topTrees = HarvestRecord::where('harvest_uuid', $this->harvestEvent->uuid)
            ->select('tree_uuid', DB::raw('SUM(num_of_fruits) as total_fruits'))
            ->groupBy('tree_uuid')
            ->orderByDesc('total_fruits')
            ->take(10)
            ->get();

        $chartData = $topTrees->map(function ($item) {
            return [
                'tree' => Tree::where('uuid', $item->tree_uuid)->value('tree_tag'),
                'total' => (int) $item->total_fruits,
            ];
        });

        return $chartData;
    }

    public function getHarvestSpeciesDataProperty()
    {
        // =========================
        // Fruits aggregation
        // =========================
        $fruitQuery = HarvestRecord::select(
            'species.id',
            'species.name as species',
            DB::raw('SUM(harvest_records.num_of_fruits) as total_pieces')
        )
            ->join('trees', 'harvest_records.tree_uuid', '=', 'trees.uuid')
            ->join('species', 'trees.species_id', '=', 'species.id')
            ->where('harvest_records.harvest_uuid', $this->harvestEvent->uuid);

        $this->applyDateFilter(
            $fruitQuery,
            'harvest_records.harvest_date'
        );

        $fruitData = $fruitQuery
            ->groupBy('species.id', 'species.name')
            ->get()
            ->keyBy('id');

        // =========================
        // Weight aggregation
        // =========================
        $weightQuery = HarvestGrade::select(
            'species.id',
            DB::raw('SUM(harvest_grade.weight) as total_weight')
        )
            ->join('species', 'harvest_grade.species_id', '=', 'species.id')
            ->where('harvest_grade.harvest_uuid', $this->harvestEvent->uuid)
            ->whereNotNull('harvest_grade.species_id');

        $this->applyDateFilter(
            $weightQuery,
            'harvest_grade.date'
        );

        $weightData = $weightQuery
            ->groupBy('species.id')
            ->pluck('total_weight', 'id');

        // =========================
        // Merge result
        // =========================
        return $fruitData->map(function ($item) use ($weightData) {
            return [
                'species' => $item->species,
                'total_pieces' => (int) $item->total_pieces,
                'total_weight' => (float) ($weightData[$item->id] ?? 0),
            ];
        })->sortByDesc('total_pieces')->values();
    }

    private function applyDateFilter($query, $column)
    {
        if ($this->fromDate) {
            $query->whereDate($column, '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate($column, '<=', $this->toDate);
        }

        return $query;
    }

    #[On('date-range-updated')]
    public function updateDateRange($fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;

        $this->dispatch(
            'refresh-harvest-species-chart',
            data: $this->harvestSpeciesData
        );
    }

    public function getTreeObservationsDataProperty()
    {
        $weights = [
            'A' => 30,
            'B' => 20,
            'C' => 15,
            'D' => 5,
            'X' => 0,
        ];

        $statuses = collect(['A', 'B', 'C', 'D', 'X']);

        $raw = TreeObservation::where('harvest_uuid', $this->harvestEvent->uuid)
            ->select('flowering_status', DB::raw('COUNT(*) as count'))
            ->groupBy('flowering_status')
            ->pluck('count', 'flowering_status');

        return $statuses->map(function ($status) use ($raw, $weights) {
            $count = $raw[$status] ?? 0;
            $multiplier = $weights[$status] ?? 0;

            return [
                'status' => $status,
                'count' => $count,
                'estimated' => $count * $multiplier,
            ];
        })->values();
    }

    public function getGradeDistributionDataProperty()
    {
        $grades = HarvestGrade::select(
            DB::raw("COALESCE(grade, 'Ungraded') as grade"),
            DB::raw('SUM(weight) as total_weight')
        )
            ->where('harvest_uuid', $this->harvestEvent->uuid)
            ->groupBy('grade')
            ->orderByDesc('total_weight')
            ->get();

        $gradeDistributionData = $grades->map(function ($item) {
            return [
                'grade' => $item->grade,
                'weight' => (float) $item->total_weight,
            ];
        })->toArray();

        return $gradeDistributionData;
    }

    public function render()
    {
        $trees = Tree::orderBy('tree_tag')->get();
        return view('livewire.module.post-harvest.harvest-event-overview-livewire', compact('trees'), [])->title(__('messages.harvest_event_overview'));
    }
}
