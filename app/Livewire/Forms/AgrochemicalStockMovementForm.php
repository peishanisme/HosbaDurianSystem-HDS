<?php

namespace App\Livewire\Forms;

use App\Models\Agrochemical;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AgrochemicalStockMovementForm extends Form
{
    public ?Agrochemical $agrochemical = null;
}
