<?php

namespace App\Livewire\Module\PostHarvest;

use App\Models\HarvestRecord;
use App\Models\Tree;
use Livewire\Attributes\On;
use Livewire\Component;

class HarvestQrCodeModalLivewire extends Component
{
    protected $listeners = ['refreshComponent' => '$refresh'];
    public string $modalID = 'harvestQrCodeModalLivewire';
    public string $modalTitle = 'Fruit QR Codes';
    public ?Tree $tree = null;
    public int $quantity = 1;
    public string $treeUrl = '';
    public string $treeTag = '';

    // public function increment()
    // {
    //     $this->quantity++;
    // }

    // public function decrement()
    // {
    //     if ($this->quantity > 1) {
    //         $this->quantity--;
    //     }
    // }

    #[On('load-qr-code')]
    public function loadQrCode(Tree $tree)
    {
        $this->tree = $tree;
        $this->quantity = 1;
        $this->treeUrl = route('public.portal', $this->tree->uuid);
        $this->treeTag = $this->tree->tree_tag;
    }

    public function resetInput(): void
    {
        $this->tree = null;
        $this->quantity = 1;
        $this->treeUrl = '';
        $this->treeTag = '';
    }

    public function printQr()
    {
        $url = route('print.qr', [
            'tree' => $this->tree->id,
            'qty' => $this->quantity
        ]);

        $this->dispatch('print-qr-url', url: $url);
    }

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-qr-code-modal-livewire');
    }
}
