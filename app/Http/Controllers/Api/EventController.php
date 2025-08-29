<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HarvestEvent;
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
}