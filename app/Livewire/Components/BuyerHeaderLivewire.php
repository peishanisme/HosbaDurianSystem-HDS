<?php

namespace App\Livewire\Components;

use App\Models\Buyer;
use Livewire\Component;

class BuyerHeaderLivewire extends Component
{
    public Buyer $buyer;
    public function render()
    {
        return view('livewire.components.buyer-header-livewire');
    }
}
