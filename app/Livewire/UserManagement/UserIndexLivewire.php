<?php

namespace App\Livewire\UserManagement;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('User Management')]
class UserIndexLivewire extends Component
{
    public function render()
    {
        return view('livewire.user-management.user-index-livewire');
    }
}
