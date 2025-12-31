<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Fruit;
use App\Models\Transaction;
use Livewire\Component;

class TransactionDetailsModalLivewire extends Component
{
    public string $modalID = 'transactionDetailsModalLivewire', $modalTitle = 'Transaction Details';
    public ?Transaction $transaction = null;
    public array $summary = [];
    public $fruits;
    protected $listeners = ['view-transaction' => 'loadTransaction'];

    public function loadTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        // dd($transaction->getSummaryAttribute());
        $this->summary = $transaction->getFruitSummary();
    }

    public function resetInput()
    {
        $this->transaction = null;
        $this->fruits = collect();
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.transaction-details-modal-livewire');
    }
}
