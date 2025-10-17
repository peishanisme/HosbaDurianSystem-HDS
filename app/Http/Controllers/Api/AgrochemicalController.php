<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgrochemicalRecord;
use App\Models\Agrochemical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Actions\AgrochemicalManagement\CreateAgrochemicalRecordAction;
use App\DataTransferObject\AgrochemicalDTO;

class AgrochemicalController extends Controller
{
     public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'agrochemical_uuid' => 'required|exists:agrochemicals,uuid',
        'tree_uuid' => 'required|exists:trees,uuid',
        'applied_at' => 'nullable|date',
        'description' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors'  => $validator->errors()
        ], 422);
    }

    $agrochemical = AgrochemicalRecord::create($validator->validated());

    return response()->json([
        'message' => 'Agrochemical created successfully',
        'data'    => $agrochemical
    ], 201);
    }

    // Fetch all agrochemicals
     public function index()
    {
        $agrochemicals = Agrochemical::all();

        return response()->json([
            'message' => 'Agrochemicals fetched successfully',
            'data'    => $agrochemicals
        ], 200);
    }


    public function update(Request $request, $id)
{
    $agrochemical = AgrochemicalRecord::findOrFail($id);

    $validated = $request->validate([
        'agrochemical_uuid' => 'required|exists:agrochemicals,uuid',
        'tree_uuid' => 'required|exists:trees,uuid',
        'applied_at' => 'nullable|date',
        'description' => 'nullable|string',
    ]);

    $dto = new AgrochemicalDTO($validated['tree_uuid'], $validated['agrochemical_uuid'], $validated['description'], $validated['applied_at']);

    $updated = (new UpdateAgrochemicalAction())->handle($agrochemical, $dto);

    return response()->json([
        'message' => 'Agrochemical updated successfully',
        'data' => $updated,
    ], 200);
}

    // Delete a agrochemical
    public function destroy($id)
    {
        $agrochemical = AgrochemicalRecord::findOrFail($id);
        $agrochemical->delete();

        return response()->json([
            'message' => 'Agrochemical deleted successfully'
        ], 200);
    }

    public function getByTree($uuid)
    {
        $records = AgrochemicalRecord::whereHas('tree', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })
        ->with(['agrochemical', 'tree'])
        ->get();

        return response()->json([
            'message' => 'Agrochemical records for tree fetched successfully',
            'data'    => $records
        ], 200);
    }
}