<?php

namespace App\Livewire\Module\PostHarvest;

use App\Models\Tree;
use App\Models\Fruit;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\HarvestEvent;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\DataTransferObject\FruitDTO;

#[Title('Harvest Events')]
class HarvestEventOverviewLivewire extends Component
{
    use SweetAlert;
    public HarvestEvent $harvestEvent;

    public $tree_id;
    public $harvested_date;
    public $grade;
    public $weight;

    #[On('close-event')]
    public function closeEvent(HarvestEvent $harvestEvent)
    {
        $this->harvestEvent = $harvestEvent;
        $this->alertConfirm('Are you sure you want to close this harvest event?', 'confirm-close');
    }

    #[On('confirm-close')]
    public function confirmClose()
    {
        try {
            $this->harvestEvent->end_date = now()->toDateString();
            $this->harvestEvent->save();
            $this->alertSuccess('Harvest event closed successfully.');
        } catch (\Exception $e) {
            $this->alertError('An error occurred while closing the harvest event: ' . $e->getMessage());
        }
    }

    #[On('reopen-event')]
    public function reopenEvent(HarvestEvent $harvestEvent)
    {
        $this->harvestEvent = $harvestEvent;
        $this->alertConfirm('Are you sure you want to reopen this harvest event?', 'confirm-reopen');
    }

    #[On('confirm-reopen')]
    public function confirmReopen()
    {
        try {
            $this->harvestEvent->end_date = null;
            $this->harvestEvent->save();
            $this->alertSuccess('Harvest event reopened successfully.');
        } catch (\Exception $e) {
            $this->alertError('An error occurred while reopening the harvest event: ' . $e->getMessage());
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

    public function loadTop5HarvestTreesData()
    {
        $topTrees = Fruit::select('tree_uuid', 'grade')
            ->where('harvest_uuid', $this->harvestEvent->uuid)
            ->get()
            ->groupBy('tree_uuid')
            ->map(function ($fruits) {
                return $fruits->groupBy('grade')->map->count();
            })
            ->sortByDesc(fn($grades) => array_sum($grades->toArray()))
            ->take(10);

        $chartData = $topTrees->map(function ($grades, $treeUuid) {
            return [
                'tree' => Tree::where('uuid', $treeUuid)->value('tree_tag'),
                'A' => $grades->get('A', 0),
                'B' => $grades->get('B', 0),
                'C' => $grades->get('C', 0),
                'D' => $grades->get('D', 0),
                'S' => $grades->get('S', 0),
                'total' => array_sum($grades->toArray()),
            ];
        })->values();

        return ($chartData);
    }

    public function loadHarvestSpeciesData()
    {
        $speciesData = Fruit::select('species.name as species', DB::raw('COUNT(*) as total'))
            ->join('trees', 'fruits.tree_uuid', '=', 'trees.uuid')
            ->join('species', 'trees.species_id', '=', 'species.id')
            ->where('fruits.harvest_uuid', $this->harvestEvent->uuid)
            ->groupBy('species.name')
            ->get()
            ->map(function ($item) {
                return [
                    'species' => $item->species,
                    'total' => $item->total,
                ];
            });

        return $speciesData;
    }

    public function loadFruitQualityData()
    {
        $counts = Fruit::where('harvest_uuid', $this->harvestEvent->uuid)
            ->select('grade', DB::raw('COUNT(*) as count'))
            ->groupBy('grade')
            ->pluck('count', 'grade')
            ->toArray();

        $orderedGrades = ['S', 'A', 'B', 'C', 'D'];

        $fruitQualityData = [];
        foreach ($orderedGrades as $grade) {
            $fruitQualityData[$grade] = $counts[$grade] ?? 0;
        }

        return $fruitQualityData;
    }

    public function loadSellingStatusData()
    {
        $total = Fruit::where('harvest_uuid', $this->harvestEvent->uuid)->count();

        $sold = Fruit::where('harvest_uuid', $this->harvestEvent->uuid)
            ->whereNotNull('transaction_uuid')
            ->count();

        $unsold = $total - $sold;

        // avoid division by zero
        if ($total == 0) {
            return [
                'sold' => 0,
                'unsold' => 0,
                'sold_percentage' => 0,
                'unsold_percentage' => 0,
            ];
        }

        return [
            'sold' => $sold,
            'unsold' => $unsold,
            'sold_percentage' => round(($sold / $total) * 100, 2),
            'unsold_percentage' => round(($unsold / $total) * 100, 2),
        ];
    }

    public function render()
    {
        $trees = Tree::orderBy('tree_tag')->get();
        return view('livewire.module.post-harvest.harvest-event-overview-livewire', compact('trees'), [
            'top5HarvestTreesData' => $this->loadTop5HarvestTreesData(),
            'harvestSpeciesData' => $this->loadHarvestSpeciesData(),
            'fruitQualityData' => $this->loadFruitQualityData(),
            'sellingStatusData' => $this->loadSellingStatusData(),
        ]);
    }
}
