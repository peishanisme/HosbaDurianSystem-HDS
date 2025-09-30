<?php

namespace App\Actions\TreeManagement;

use App\DataTransferObject\TreeGrowthLogDTO;
use App\Models\TreeGrowthLog;

class UpdateGrowthLogAction
{
    public function handle(TreeGrowthLogDTO $dto): TreeGrowthLog
    {
        return TreeGrowthLog::create([
            'tree_id'   => $dto->tree_id,
            'tree_uuid' => $dto->tree_uuid,
            'height'    => $dto->height,
            'diameter'  => $dto->diameter,
            'photo'     => $dto->photo,
        ]);
    }
}
