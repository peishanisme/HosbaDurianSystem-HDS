<?php

namespace App\Livewire\Module\UserManagement;

use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Activity Log')]
class ActivityLogIndexLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-activity-log']);
    }

    public function render()
    {
        return view('livewire.module.user-management.activity-log-index-livewire');
    }
}
