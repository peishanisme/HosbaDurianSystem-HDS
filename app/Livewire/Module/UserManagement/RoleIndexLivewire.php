<?php

namespace App\Livewire\Module\UserManagement;

use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;
use Livewire\Attributes\Title;

class RoleIndexLivewire extends Component
{
    use AuthorizesRoleOrPermission;

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-roles']);
    }
    
    public function render()
    {
        return view('livewire.module.user-management.role-index-livewire')->title(__('messages.role_listing'));
    }
}
