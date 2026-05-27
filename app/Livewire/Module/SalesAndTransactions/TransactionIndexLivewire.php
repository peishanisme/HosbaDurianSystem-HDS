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
    public ?Transaction $transaction = null;
    public $expandedDates = [];
    public ?string $fromDate = null;
    public ?string $toDate = null;

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-sale']);
    }

    public function getTransactionsProperty()
    {
        return Transaction::query()

            ->when($this->fromDate, function ($query) {
                $query->whereDate('date', '>=', $this->fromDate);
            })

            ->when($this->toDate, function ($query) {
                $query->whereDate('date', '<=', $this->toDate);
            })

            ->orderBy('date', 'desc')

            ->get();
    }

    public function getTransactionByDateProperty()
    {
        return $this->transactions
            ->groupBy('date');
    }

    public function getTotalTransactionsProperty()
    {
        return $this->transactions->count();
    }

    public function getTotalWeightProperty()
    {
        return $this->transactions
            ->sum('total_weight');
    }

    public function getTotalAmountProperty()
    {
        return $this->transactions
            ->sum('total_amount');
    }

    #[On('date-range-updated')]
    public function updateDateRange($fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
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
