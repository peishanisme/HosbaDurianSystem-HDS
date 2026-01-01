<?php

namespace App\Livewire\Module\SalesAndTransactions\TransactionStep;

use App\Models\Buyer;
use Livewire\Component;

class BuyerStep extends Component
{
    public array $buyerOptions = [];

    public function mount()
    {
        $this->buyerOptions = Buyer::all()->mapWithKeys(function ($buyer) {
            return [$buyer->uuid => $buyer->company_name];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.transaction-step.buyer-step');
    }
}
