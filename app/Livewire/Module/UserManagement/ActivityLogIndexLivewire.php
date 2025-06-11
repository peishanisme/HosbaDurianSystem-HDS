<?php

namespace App\Livewire\Module\UserManagement;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Activity Log')]
class ActivityLogIndexLivewire extends Component
{
    public function render()
    {
        return view('livewire.module.user-management.activity-log-index-livewire');
    }
}
