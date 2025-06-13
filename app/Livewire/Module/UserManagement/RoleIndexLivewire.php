<?php

namespace App\Livewire\Module\UserManagement;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('User Management')]
class RoleIndexLivewire extends Component
{
    public function render()
    {
        return view('livewire.module.user-management.role-index-livewire');
    }
}
