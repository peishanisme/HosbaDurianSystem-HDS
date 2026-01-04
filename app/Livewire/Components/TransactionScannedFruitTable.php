<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class TransactionScannedFruitTable extends Component
{
    public array $scannedFruits = [];

    #[On('refreshTable')]
    public function updateTable($scannedFruits)
    {
        $this->scannedFruits = $scannedFruits;
    }

    public function remove($uuid)
    {
        $this->dispatch('removeFruit', $uuid);
    }

    public function render()
    {
        return view('livewire.components.transaction-scanned-fruit-table');
    }
}
