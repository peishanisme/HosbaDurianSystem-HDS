<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\Agrochemical;
use Livewire\Attributes\Title;
use App\Models\AgrochemicalStockMovement;
use App\Traits\AuthorizesRoleOrPermission;

class AgrochemicalPurchaseHistoryLivewire extends Component
{    
    use SweetAlert, AuthorizesRoleOrPermission;

    public Agrochemical $agrochemical;

    public AgrochemicalStockMovement $stock;
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-fertilizer-pesticide']);
    }

    #[On('delete-stock')]
    public function deleteStock(AgrochemicalStockMovement $stock)
    {
        $this->stock = $stock;
        $this->alertConfirm(__('messages.are_you_sure_delete'), 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->stock->delete();
        $this->alertSuccess(__('messages.purchase_history_deleted_successfully'));
    }
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-purchase-history-livewire')->title(__('messages.agrochemical_purchase_history') );
    }
}
