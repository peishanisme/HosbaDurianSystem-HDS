<?php

namespace App\Actions\AgrochemicalManagement;

use App\DataTransferObject\AgrochemicalDTO;
use App\Models\AgrochemicalRecord;

class CreateAgrochemicalRecordAction
{
    public function handle(AgrochemicalDTO $dto): AgrochemicalRecord
    {
        return AgrochemicalRecord::create($dto->toArray());
    }
}
