<?php

namespace App\Actions\TreeManagement\Health;

use App\DataTransferObject\HealthRecordDTO;
use App\Models\HealthRecord;

class UpdateHealthAction
{
    public function handle(HealthRecord $healthRecord, HealthRecordDTO $dto): HealthRecord
    {
        return tap($healthRecord)->update($dto->toArray());
    }
}
