<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Agrochemical Usage')]
class AgrochemicalGlobalUsageLivewire extends Component
{
    
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-global-usage-livewire');
    }
}
