<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Livewire\Component;
use App\Models\Agrochemical;
use Livewire\Attributes\Title;
use App\Traits\AuthorizesRoleOrPermission;

class AgrochemicalOverviewLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    public Agrochemical $agrochemical;
    
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-fertilizer-pesticide']);
    }
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-overview-livewire')->title(__('messages.agrochemical_details'));
    }
}
