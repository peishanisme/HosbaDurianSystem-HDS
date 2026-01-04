<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use App\Models\Agrochemical;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Agrochemical Application Records')]
class AgrochemicalApplicationRecordLivewire extends Component
{
    public Agrochemical $agrochemical;
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-application-record-livewire');
    }
}
