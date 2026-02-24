<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Buyer;
use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

class BuyerIndexLivewire extends Component
{
     use SweetAlert, AuthorizesRoleOrPermission;
    public Buyer $buyer;

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-buyer']);
    }
    
    #[On('delete-buyer')]
    public function deleteBuyer(Buyer $buyer)
    {
        $this->buyer = $buyer;
        $this->alertConfirm(__('messages.are_you_sure_you_want_to_delete_this_buyer'), 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->buyer->delete();
        $this->alertSuccess(__('messages.buyer_deleted_successfully'));
    }
    public function render()
    {
        return view('livewire.module.sales-and-transactions.buyer-index-livewire')->title(__('messages.buyer_listing'));
    }
}
