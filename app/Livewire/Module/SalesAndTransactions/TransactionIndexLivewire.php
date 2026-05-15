<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Transaction;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class TransactionIndexLivewire extends Component
{
    use AuthorizesRoleOrPermission, SweetAlert;
    protected $listeners = ['refreshComponent' => '$refresh'];
    public $transactions;
    public ?Transaction $transaction = null;
    public $expandedDates = [];
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-sale']);
        $this->transactions = Transaction::orderBy('date', 'desc')->get();
    }

    public function getTransactionByDateProperty()
    {
        return $this->transactions->groupBy(function ($transaction) {
            return $transaction->date;
        });
    }

    public function toggleExpand($date)
    {
        if (in_array($date, $this->expandedDates)) {
            $this->expandedDates = array_values(
                array_diff($this->expandedDates, [$date])
            );
        } else {
            $this->expandedDates[] = $date;
        }
    }

    #[On('delete-transaction')]
    public function deleteTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->alertConfirm('Are you sure you want to delete this transaction?', 'confirm-delete-transaction');
    }

    #[On('confirm-delete-transaction')]
    public function confirmDeleteTransaction()
    {
        try {
            $this->transaction->delete();
            $this->alertSuccess('Transaction deleted successfully');
            $this->transaction = null;
        } catch (\Exception $e) {
            $this->alertError('Failed to delete transaction: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.transaction-index-livewire')->title(__('messages.transaction_listing'));
    }
}
