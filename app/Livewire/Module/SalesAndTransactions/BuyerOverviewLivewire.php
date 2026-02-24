<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Buyer;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Traits\AuthorizesRoleOrPermission;

class BuyerOverviewLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    public Buyer $buyer;
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-buyer']);
    }
    
    public function render()
    {
        return view('livewire.module.sales-and-transactions.buyer-overview-livewire')->title(__('messages.buyer_overview'));
    }
}
