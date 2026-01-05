<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ModalButton extends Component
{
    public ?string $permission = null;
    public ?string $dispatch = null;
    public ?string $target = null;
    public ?string $label = null;
    public function render()
    {
        return view('livewire.components.modal-button');
    }
}
