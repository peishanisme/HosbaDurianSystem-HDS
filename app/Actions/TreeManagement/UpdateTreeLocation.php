<?php

namespace App\Actions\TreeManagement;

use App\Models\Tree;
use Illuminate\Support\Facades\DB;
use App\DataTransferObject\TreeDTO;

class UpdateTreeLocation
{
    public function execute(Tree $tree, float $latitude, float $longitude): Tree
    {
        $tree->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        return $tree;
    }
}