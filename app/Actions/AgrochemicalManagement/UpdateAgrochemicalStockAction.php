<?php

namespace App\Actions\AgrochemicalManagement;

use App\Models\AgrochemicalStockMovement;
use App\DataTransferObject\AgrochemicalStockMovementDTO;

class UpdateAgrochemicalStockAction
{
    public function handle(AgrochemicalStockMovement $stock, AgrochemicalStockMovementDTO $dto): AgrochemicalStockMovement
    {
        return tap($stock)->update($dto->toArray());
    }
}
