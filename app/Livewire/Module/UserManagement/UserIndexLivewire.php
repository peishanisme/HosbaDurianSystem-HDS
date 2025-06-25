<?php

namespace App\Livewire\Module\UserManagement;

use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('User Management')]
class UserIndexLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-user']);
    }
    public function render()
    {
        return view('livewire.module.user-management.user-index-livewire');
    }
}
