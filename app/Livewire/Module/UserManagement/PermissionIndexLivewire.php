<?php

namespace App\Livewire\Module\UserManagement;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('User Management')]
class PermissionIndexLivewire extends Component
{
    public function render()
    {
        return view('livewire.module.user-management.permission-index-livewire');
    }
}
