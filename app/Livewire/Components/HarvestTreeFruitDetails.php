<?php

namespace App\Livewire\Components;

use App\Models\FruitFeedback;
use App\Models\Tree;
use App\Models\Species;
use Livewire\Component;
use App\Models\HarvestEvent;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HarvestTreeFruitDetails extends Component
{
    public $expandedTreeUuid = null;

    public $harvestEvent;
    public $search = '';
    public $filterSpecies = '';
    public $expanded = [];
    public $qrCode;
    public $fruitUuid, $fruitTag;
    public $feedback;

    protected $queryString = ['search', 'filterSpecies'];

    public function toggleTree($uuid)
    {
        $this->expandedTreeUuid = $this->expandedTreeUuid === $uuid ? null : $uuid;
    }


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

    public function showQrCode($uuid)
    {
        $this->fruitUuid = $uuid;
        $this->fruitTag = Tree::whereHas('fruits', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->first()->fruits()->where('uuid', $uuid)->first()->fruit_tag;

        // Generate QR code as HTML string (make sure it's plain string)
        $this->qrCode = (string) QrCode::size(300)->generate(route('public.portal', $this->fruitUuid));

        // Dispatch browser event to open the modal
        $this->dispatch('show-qr-modal');
    }

    public function showFeedback($uuid)
    {
        $this->fruitUuid = $uuid;
        $this->feedback = FruitFeedback::where('fruit_uuid', $this->fruitUuid)->orderBy('created_at', 'desc')
            ->get();
        // Dispatch browser event to open the modal
        $this->dispatch('show-feedback-modal');
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
