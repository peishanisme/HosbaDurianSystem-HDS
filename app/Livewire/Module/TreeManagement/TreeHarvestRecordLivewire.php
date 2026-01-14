<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use Livewire\Component;
use App\Models\HarvestEvent;
use App\Models\FruitFeedback;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

#[Title('Tree Harvest Record')]
class TreeHarvestRecordLivewire extends Component
{
    public Tree $tree;
    public $search = '';
    public $filterYear = '';
    public $expanded = [];
    public $qrCode;
    public $fruitUuid, $fruitTag;
    public $feedback;

    public function toggleExpand($harvestUuid)
    {
        if (in_array($harvestUuid, $this->expanded)) {
            $this->expanded = array_diff($this->expanded, [$harvestUuid]);
        } else {
            $this->expanded[] = $harvestUuid;
        }
    }

    public function getHarvestsProperty()
    {
        return HarvestEvent::query()
            ->whereHas('fruits', fn($q) => $q->where('tree_uuid', $this->tree->uuid))
            ->with(['fruits' => fn($q) => $q->where('tree_uuid', $this->tree->uuid)])
            ->when($this->search, fn($q) => $q->where('event_name', 'like', "%{$this->search}%"))
            ->when($this->filterYear, fn($q) => $q->whereYear('start_date', $this->filterYear))
            ->orderByDesc('start_date')
            ->get();
    }

    public function showQrCode($uuid)
    {
        $this->fruitUuid = $uuid;
        $this->fruitTag = Tree::whereHas('fruits', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->first()->fruits()->where('uuid', $uuid)->first()->fruit_tag;

        $this->qrCode = (string) QrCode::size(300)
            ->generate(route('public.portal', $this->fruitUuid));

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
        $years = DB::table('harvest_events')
            ->selectRaw('DISTINCT DATE_PART(\'year\', start_date) AS year')
            ->whereNull('deleted_at')
            ->orderByDesc('year')
            ->get();

        return view('livewire.module.tree-management.tree-harvest-record-livewire', [
            'harvests' => $this->harvests,
            'years' => $years,
        ]);
    }
}
