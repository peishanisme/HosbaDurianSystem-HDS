<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\Agrochemical;
use Livewire\Attributes\Title;
use App\Models\AgrochemicalStockMovement;
use App\Traits\AuthorizesRoleOrPermission;

#[Title('Agrochemical Management')]
class AgrochemicalPurchaseHistoryLivewire extends Component
{    
    use SweetAlert, AuthorizesRoleOrPermission;

    public Agrochemical $agrochemical;

    public AgrochemicalStockMovement $stock;

    #[On('delete-stock')]
    public function deleteStock(AgrochemicalStockMovement $stock)
    {
        $this->stock = $stock;
        $this->alertConfirm('Are you sure you want to delete this purchase history?', 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->stock->delete();
        $this->alertSuccess('Purchase History deleted successfully.');
    }
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-purchase-history-livewire');
    }
}
