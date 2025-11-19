<?php

namespace App\Livewire\Module;

use Livewire\Component;

class PublicPortalLivewire extends Component
{
    public function render()
    {
        return view('livewire.module.public-portal-livewire')->layout('components.layouts.site');
    }
}
