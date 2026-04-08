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
    public string $modalTitle = 'Harvest QR Code';
    public ?HarvestRecord $harvestRecord = null;
    public ?Tree $tree = null;
    public int $quantity = 1;

    public function increment()
    {
        $this->quantity++;
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    #[On('load-qr-code')]
    public function loadQrCode(HarvestRecord $harvestRecord)
    {
        $this->harvestRecord = $harvestRecord;
        $this->tree = Tree::where('uuid', $harvestRecord->tree_uuid)->first();
        $this->quantity = $harvestRecord->num_of_fruits;
    }

    public function resetInput(): void
    {
        $this->harvestRecord = null;
        $this->tree = null;
        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-qr-code-modal-livewire');
    }
}
