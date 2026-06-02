<?php

namespace App\Livewire\Components\Headers;

use App\Models\Label;
use App\Models\Tree;
use App\Traits\SweetAlert;
use Livewire\Component;

class TreeDetailsHeader extends Component
{
    use SweetAlert;
    public Tree $tree;
    public string $treeUrl = '';
    protected $listeners = ['refreshComponent' => '$refresh'];
    public $labelOptions;
    public $selectedLabels = [];

    public function mount()
    {
        $this->treeUrl = route('public.portal', $this->tree->uuid);
        $this->labelOptions = Label::select('id', 'name', 'color')->get();
        $this->selectedLabels = $this->tree->labels->pluck('id')->toArray();
    }

    public function saveLabels()
    {
        $this->tree->labels()->sync($this->selectedLabels);
        $this->alertSuccess('Labels updated successfully!');
        $this->dispatch('refreshComponent');
        $this->dispatch('closeDrawer');
    }

    public function render()
    {
        return view('livewire.components.headers.tree-details-header');
    }
}
