<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Fruit;
use App\Models\Transaction;
use App\Traits\SweetAlert;
use Livewire\Component;

class TransactionDetailsModalLivewire extends Component
{
    use SweetAlert;
    public string $modalID = 'transactionDetailsModalLivewire', $modalTitle = 'Transaction Details';
    public ?Transaction $transaction = null;
    public array $summary = [];
    public $fruits;
    protected $listeners = ['view-transaction' => 'loadTransaction'];

    public function loadTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->summary = $transaction->getFruitSummary();
    }

    public function resetInput()
    {
        $this->transaction = null;
        $this->fruits = collect();
    }

    public function printReceipt()
    {
        if (!$this->transaction) {
            $this->toastError('No transaction loaded.');
            return;
        }

        $this->dispatch('close-modal', modalID: $this->modalID);

        return redirect()->route('receipt.print', [
            'transaction' => $this->transaction->uuid,
        ]);
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.transaction-details-modal-livewire');
    }
}
