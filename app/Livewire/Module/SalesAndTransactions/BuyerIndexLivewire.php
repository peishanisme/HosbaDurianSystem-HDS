<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Buyer;
use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Buyer Management')]
class BuyerIndexLivewire extends Component
{
     use SweetAlert, AuthorizesRoleOrPermission;
    public Buyer $buyer;

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-buyer']);
    }
    
    #[On('delete-buyer')]
    public function deleteTree(Buyer $buyer)
    {
        $this->buyer = $buyer;
        $this->alertConfirm('Are you sure you want to delete this buyer?', 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->buyer->delete();
        $this->alertSuccess('Buyer deleted successfully.');
    }
    public function render()
    {
        return view('livewire.module.sales-and-transactions.buyer-index-livewire');
    }
}
