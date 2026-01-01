<?php

namespace App\Livewire\Module\SalesAndTransactions\TransactionStep;

use Livewire\Component;
use Livewire\Attributes\On;

class ScanFruitStep extends Component
{public array $scannedFruits = [];

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
        return view('livewire.module.sales-and-transactions.transaction-step.scan-fruit-step');
    }
}
