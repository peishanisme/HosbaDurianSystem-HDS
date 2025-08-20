<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTransferObject\DiseaseDTO;
use App\Actions\TreeManagement\Health\UpdateDiseaseAction;

class DiseaseController extends Controller
{
    // Create a new disease
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'diseaseName'        => 'required|string|max:255',
            'symptoms'        => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $disease = Disease::create($validator->validated());

        return response()->json([
            'message' => 'Disease created successfully',
            'data'    => $disease
        ], 201);
    }

    // Fetch all diseases
    public function index() 
    {
        $diseases = Disease::all();

        return response()->json([
            'message' => 'Diseases fetched successfully',
            'data'    => $diseases
        ], 200);
    }


    public function update(Request $request, $id)
{
    $disease = Disease::findOrFail($id);

    $validated = $request->validate([
        'diseaseName' => 'required|string|max:255',
        'symptoms' => 'nullable|string',
        'remarks' => 'nullable|string',
    ]);

    $dto = new DiseaseDTO($validated['diseaseName'], $validated['symptoms'], $validated['remarks']);

    $updated = (new UpdateDiseaseAction())->handle($disease, $dto);

    return response()->json([
        'message' => 'Disease updated successfully',
        'data' => $updated,
    ], 200);
}

    // Delete a disease
    public function destroy($id)
    {
        $disease = Disease::findOrFail($id);
        $disease->delete();

        return response()->json([
            'message' => 'Disease deleted successfully'
        ], 200);
    }
}
