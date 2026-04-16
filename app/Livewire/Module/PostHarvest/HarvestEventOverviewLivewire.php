<?php

namespace App\Livewire\Module\PostHarvest;

use App\DataTransferObject\FruitDTO;
use App\Models\Fruit;
use App\Models\HarvestEvent;
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

    public function save()
    {

        $this->validate([
            'tree_id' => 'required',
            'harvested_date' => 'required|date',
            'grade' => 'required',
            'weight' => 'required|numeric|min:0',
        ]);

        $tree = Tree::find($this->tree_id);

        $fruitDTO = new FruitDTO(
            harvest_uuid: $this->harvestEvent->uuid,
            transaction_uuid: null,
            harvested_at: $this->harvested_date,
            is_spoiled: false,
            tree_uuid: $tree->uuid ?? null,
            weight: $this->weight,
            grade: $this->grade
        );

        app('App\Actions\FruitManagement\CreateFruitAction')->handle($fruitDTO);

        session()->flash('message', 'Harvest saved successfully!');

        // Reset form after save
        $this->reset(['tree_id', 'harvested_date', 'grade', 'weight']);
    }

    public function update()
    {
        $data = $this->validate([
            'tree_id' => 'required',
            'harvested_date' => 'required|date',
            'grade' => 'required',
            'weight' => 'required|numeric|min:0',
        ]);

        $tree = Tree::find($this->tree_id);

        $fruitDTO = new FruitDTO(
            harvest_uuid: $this->harvestEvent->uuid,
            transaction_uuid: null,
            harvested_at: $this->harvested_date,
            is_spoiled: false,
            tree_uuid: $tree->uuid ?? null,
            weight: $this->weight,
            grade: $this->grade
        );

        app('App\Actions\FruitManagement\UpdateFruitAction')->handle($fruitDTO, $this->fruit->uuid);

        session()->flash('message', 'Fruit updated successfully!');

        // Reset form after update
        $this->reset(['tree_id', 'harvested_date', 'grade', 'weight']);
    }

    public function loadTop10HarvestTreesData()
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

    public function loadHarvestSpeciesData()
    {
        $speciesData = HarvestRecord::select(
            'species.name as species',
            DB::raw('SUM(harvest_records.num_of_fruits) as total_pieces'),
            DB::raw('SUM(harvest_records.weight) as total_weight')
        )
            ->join('trees', 'harvest_records.tree_uuid', '=', 'trees.uuid')
            ->join('species', 'trees.species_id', '=', 'species.id')
            ->where('harvest_records.harvest_uuid', $this->harvestEvent->uuid)
            ->groupBy('species.name')
            ->orderByDesc('total_pieces')
            ->get()
            ->map(function ($item) {
                return [
                    'species' => $item->species,
                    'total_pieces' => (int) $item->total_pieces,
                    'total_weight' => (float) $item->total_weight,
                ];
            });

        return $speciesData;
    }

    public function loadTreeObservationsData()
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

    public function render()
    {
        $trees = Tree::orderBy('tree_tag')->get();
        return view('livewire.module.post-harvest.harvest-event-overview-livewire', compact('trees'), [
            'treeObservationsData' => $this->loadTreeObservationsData(),
            'top10HarvestTreesData' => $this->loadTop10HarvestTreesData(),
            'harvestSpeciesData' => $this->loadHarvestSpeciesData(),
        ])->title(__('messages.harvest_event_overview'));
    }
}
