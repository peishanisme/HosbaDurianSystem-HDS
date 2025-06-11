<?php

namespace App\Livewire\Module\SalesAndTransactions;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Sales & Transactions')]
class BuyerIndexLivewire extends Component
{
    public function render()
    {
        return view('livewire.module.sales-and-transactions.buyer-index-livewire');
    }
}
