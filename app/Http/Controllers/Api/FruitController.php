<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fruit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTransferObject\FruitDTO;
use Illuminate\Support\Str;
use App\Actions\FruitManagement\CreateFruitAction;

class FruitController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tree_uuid' => 'required|exists:trees,uuid',
            'harvest_uuid' => 'required|exists:harvest_events,uuid',
            'transaction_uuid' => 'nullable|string',
            'harvested_at' => 'nullable|date',
            'weight' => 'required|numeric',
            'grade' => 'required|string|max:255',
            'is_spoiled' => 'nullable|boolean',
        ]);

        $dto = FruitDTO::fromArray($validated);

        $record = (new CreateFruitAction())->handle($dto);

        return response()->json([
            'message' => 'Fruit created successfully',
            'data' => $record
        ], 201);
    }

    // public function index() 
    // {
    //     $records = Fruit::all();

    //     return response()->json([
    //         'message' => 'Fruit records fetched successfully',
    //         'data'    => $records
    //     ], 200);
    // }

    public function index()
    {
        $fruits = Fruit::with('tree.species')->get();
        return response()->json($fruits);
    }



    // public function update(Request $request, $id)
    // {
    //     $healthRecord = HealthRecord::findOrFail($id);

    //     $validated = $request->validate([
    //         'tree_uuid' => 'required|exists:trees,uuid',
    //         'disease_id' => 'required|exists:diseases,id',
    //         'status' => 'required|string|max:255',
    //         'recorded_at' => 'nullable|date',
    //         'treatment' => 'nullable|string',
    //     ]);

    //     // Create DTO
    //     $dto = new HealthRecordDTO(
    //     id: $healthRecord->id,
    //     disease_id: (int) $validated['disease_id'],
    //     status: $validated['status'],
    //     recorded_at: $validated['recorded_at'] ?? null,
    //     treatment: $validated['treatment'] ?? null
    // );

    //     $updated = (new UpdateHealthAction())->handle($healthRecord, $dto);

    //     return response()->json([
    //         'message' => 'Health record updated successfully',
    //         'data'    => $updated,
    //     ], 200);
    // }


    // public function destroy($id)
    // {
    //     $healthRecord = HealthRecord::findOrFail($id);
    //     $healthRecord->delete();

    //     return response()->json([
    //         'message' => 'Health record deleted successfully'
    //     ], 200);
    // }

    // public function getByTree($uuid)
    // {
    //     $records = HealthRecord::whereHas('tree', function ($query) use ($uuid) {
    //         $query->where('uuid', $uuid);
    //     })->with('disease')->get();

    //     return response()->json([
    //         'message' => 'Health records for tree fetched successfully',
    //         'data'    => $records
    //     ], 200);
    // }
}
