<?php

namespace App\Livewire\Module;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Dashboard')]
class DashboardLivewire extends Component
{
    public function render()
    {
        return view('livewire.dashboard-livewire');
    }
}
