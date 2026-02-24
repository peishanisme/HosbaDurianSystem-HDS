<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Traits\AuthorizesRoleOrPermission;

class AgrochemicalGlobalUsageLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-fertilization-activity']);
    }
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-global-usage-livewire')
            ->title(__('messages.agrochemical_usages_listing'));
    }
}
