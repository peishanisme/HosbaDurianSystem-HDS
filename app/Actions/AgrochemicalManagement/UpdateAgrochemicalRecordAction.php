<?php

namespace App\Actions\AgrochemicalManagement;

use App\Models\AgrochemicalRecord;
use App\DataTransferObject\AgrochemicalRecordDTO;

class UpdateAgrochemicalRecordAction
{
    public function handle(AgrochemicalRecord $record, AgrochemicalRecordDTO $dto): AgrochemicalRecord
    {
        $data = array_filter($dto->toArray(), fn ($value) => $value !== null);

        $record->update($data);

        return $record->fresh();
    }
}

