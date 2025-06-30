<?php

namespace App\Actions\AgrochemicalManagement;

use App\DataTransferObject\AgrochemicalStockMovementDTO;
use App\Models\AgrochemicalStockMovement;

class CreateAgrochemicalStockAction
{
    public function handle(AgrochemicalStockMovementDTO $dto): AgrochemicalStockMovement
    {
        return AgrochemicalStockMovement::create($dto->toArray());
    }
}
