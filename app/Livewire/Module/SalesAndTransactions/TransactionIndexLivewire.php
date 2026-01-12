<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Sales & Transactions')]
class TransactionIndexLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-sale']);
    }
    public function render()
    {
        return view('livewire.module.sales-and-transactions.transaction-index-livewire');
    }
}
