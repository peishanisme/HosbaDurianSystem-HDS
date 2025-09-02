<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HarvestEvent;
use App\Models\Fruit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Actions\PostHarvest\CreateHarvestEventAction;
use App\DataTransferObject\HarvestEventDTO;

class EventController extends Controller
{
    public function index() 
    {
        $records = HarvestEvent::all();

        return response()->json([
            'message' => 'Harvest events fetched successfully',
            'data'    => $records
        ], 200);
    }

    public function getTreeHarvestEvents($treeUuid)
    {
        $harvestEvents = HarvestEvent::whereHas('fruits', function ($query) use ($treeUuid) {
            $query->where('tree_uuid', $treeUuid);
        })
        ->with(['fruits' => function ($query) use ($treeUuid) {
            $query->where('tree_uuid', $treeUuid);
        }])
        ->get();

        if ($harvestEvents->isEmpty()) {
            return response()->json([
                'message' => 'No harvest events found for this tree',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Harvest events retrieved successfully',
            'data' => $harvestEvents,
        ], 200);
    }
}