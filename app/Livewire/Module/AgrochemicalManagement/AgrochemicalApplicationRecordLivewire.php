<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Livewire\Component;
use App\Models\Agrochemical;
use Livewire\Attributes\Title;
use App\Traits\AuthorizesRoleOrPermission;

class AgrochemicalApplicationRecordLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-fertilization-activity']);
    }
    public Agrochemical $agrochemical;
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-application-record-livewire')->title(__('messages.agrochemical_application_records'));
    }
}
