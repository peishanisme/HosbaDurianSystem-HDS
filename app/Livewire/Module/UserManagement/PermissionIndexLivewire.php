<?php

namespace App\Livewire\Module\UserManagement;

use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;
use Livewire\Attributes\Title;

class PermissionIndexLivewire extends Component
{
    use AuthorizesRoleOrPermission;

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-permissions']);
    }
    
    public function render()
    {
        return view('livewire.module.user-management.permission-index-livewire')->title(__( 'messages.permission_listing' ));
    }
}
