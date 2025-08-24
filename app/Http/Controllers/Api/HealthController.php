<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTransferObject\HealthRecordDTO;
// use App\Actions\TreeManagement\Health\UpdateDiseaseAction;

class HealthController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tree_uuid' => 'required|exists:trees,uuid',
            'disease_id' => 'required|exists:diseases,id',
            'status' => 'required|string|max:255',
            'recorded_at' => 'nullable|date',
            'treatment' => 'nullable|string',
        ]);

        $record = HealthRecord::create($validated);

        return response()->json([
            'message' => 'Health record created successfully',
            'data' => $record
        ], 201);
    }


    // Fetch all diseases
    public function index() 
    {
        $records = HealthRecord::all();

        return response()->json([
            'message' => 'Health records fetched successfully',
            'data'    => $records
        ], 200);
    }


    public function update(Request $request, $id)
{
    $healthRecord = HealthRecord::findOrFail($id);

    $validated = $request->validate([
        'disease_id' => 'required|exists:diseases,id',
        'status' => 'required|string|max:255',
        'recorded_at' => 'nullable|date',
        'treatment' => 'nullable|string',
    ])->merge(['disease_id' => $healthRecord->disease_id]);

    $dto = new HealthRecordDTO($validated['disease_id'], $validated['status'], $validated['recorded_at'], $validated['treatment']);

    $updated = (new UpdateDiseaseAction())->handle($healthRecord, $dto);

    return response()->json([
        'message' => 'Health record updated successfully',
        'data' => $updated,
    ], 200);
}

    public function destroy($id)
    {
        $healthRecord = HealthRecord::findOrFail($id);
        $healthRecord->delete();

        return response()->json([
            'message' => 'Health record deleted successfully'
        ], 200);
    }

    public function getByTree($uuid)
    {
        $records = HealthRecord::whereHas('tree', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->with('disease')->get();

        return response()->json([
            'message' => 'Health records for tree fetched successfully',
            'data'    => $records
        ], 200);
    }
}
