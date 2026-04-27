<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Label;
use App\Models\Tree;
use App\Traits\SweetAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TreeLabelModalLivewire extends Component
{
    use SweetAlert, WithPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];
    public string $modalID = 'treeLabelModalLivewire', $modalTitle = 'Manage Tree Labels';
    protected string $paginationTheme = 'bootstrap';

    public $selectedLabel, $labelsOptions;
    public $trees, $selectedTrees;
    public $labelTreeMap = [];
    public string $search = '';
    public $showCreateLabel = false;
    public $newLabelName = '';
    public $newLabelColor = '#0d6efd'; // default color

    public function mount()
    {
        $this->labelsOptions = Label::all();
        $this->trees = Tree::with('labels')->orderBy('id')->get();
        $this->selectedTrees = [];
        $this->labelTreeMap = DB::table('tree_label')
            ->get()
            ->groupBy('label_id')
            ->map(fn($items) => $items->pluck('tree_id')->toArray())
            ->toArray();
    }

    public function updatedSelectedLabel($value)
    {
        if ($value) {
            $this->selectedTrees = $this->labelTreeMap[$value] ?? [];
        } else {
            $this->selectedTrees = [];
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

     public function resetInput(): void
    {
        $this->reset('search', 'selectedLabel', 'selectedTrees');
    }

    public function createLabel()
    {
        $this->validate([
            'newLabelName' => 'required|string|max:255',
            'newLabelColor' => 'required|string',
        ]);

        $label = Label::create([
            'name' => $this->newLabelName,
            'label' => $this->newLabelName,
            'color' => $this->newLabelColor,
        ]);

        // refresh dropdown
        $this->labelsOptions = Label::all();

        // auto select newly created label
        $this->selectedLabel = $label->id;

        $this->toastSuccess('Label created successfully');
        // reset form
        $this->reset(['newLabelName', 'newLabelColor', 'showCreateLabel']);
    }

    public function getFilteredTreesProperty()
    {
        return Tree::with('labels:id,name,color')
            ->orderBy('id')
            ->when($this->search, function ($query) {
                $query->whereRaw('LOWER(tree_tag) LIKE ?', ['%' . strtolower($this->search) . '%']);
            })
            ->paginate(50);
    }

    public function update()
    {
        $labelId = $this->selectedLabel;
        $newTreeIds = $this->selectedTrees;

        // Get current tree IDs for the label
        $currentTreeIds = $this->labelTreeMap[$labelId] ?? [];

        // Determine which tree IDs to add and which to remove
        $toAdd = array_diff($newTreeIds, $currentTreeIds);
        $toRemove = array_diff($currentTreeIds, $newTreeIds);

        try {
            // Update the pivot table
            if (!empty($toAdd)) {
                DB::table('tree_label')->insert(
                    array_map(fn($treeId) => ['tree_id' => $treeId, 'label_id' => $labelId], $toAdd)
                );
            }

            if (!empty($toRemove)) {
                DB::table('tree_label')
                    ->where('label_id', $labelId)
                    ->whereIn('tree_id', $toRemove)
                    ->delete();
            }

            // Refresh the label-tree mapping
            $this->labelTreeMap = DB::table('tree_label')
                ->get()
                ->groupBy('label_id')
                ->map(fn($items) => $items->pluck('tree_id')->toArray())
                ->toArray();

            $this->alertSuccess('Labels successfully updated', $this->modalID);
        } catch (\Exception $e) {
            $this->alertError($e->getMessage(), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.tree-management.tree-label-modal-livewire');
    }
}
