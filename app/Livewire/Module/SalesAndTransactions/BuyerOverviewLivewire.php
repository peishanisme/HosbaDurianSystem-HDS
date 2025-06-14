<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Buyer;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Sales & Transactions')]
class BuyerOverviewLivewire extends Component
{
    public Buyer $buyer;
    public function render()
    {
        return view('livewire.module.sales-and-transactions.buyer-overview-livewire');
    }
}
