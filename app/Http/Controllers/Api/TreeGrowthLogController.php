<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Actions\TreeManagement\UpdateGrowthLogAction;
use App\Models\TreeGrowthLog;
use App\DataTransferObject\TreeGrowthLogDTO;

class TreeGrowthLogController extends Controller
{
    public function store(Request $request, UpdateGrowthLogAction $updateGrowthLogAction)
    {
        $validated = $request->validate([
            'tree_id'   => 'required|exists:trees,id',
            'tree_uuid' => 'required|exists:trees,uuid',
            'height'    => 'required|numeric|min:0',
            'diameter'  => 'required|numeric|min:0',
            'photo'     => 'nullable|string',
        ]);

        $dto = TreeGrowthLogDTO::fromArray($validated);

        $growthLog = $updateGrowthLogAction->handle($dto);

        return response()->json([
            'message' => 'Tree growth log updated successfully',
            'data'    => $growthLog,
        ], 200);
    }

    public function getByTreeUuid($uuid)
    {
        $growthLogs = TreeGrowthLog::where('tree_uuid', $uuid)->get();

        return response()->json([
            'message' => 'Growth logs fetched successfully',
            'data'    => $growthLogs,
        ], 200);
    }
}
