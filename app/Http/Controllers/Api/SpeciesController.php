<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Species;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTransferObject\SpeciesDTO;
use App\Actions\TreeManagement\UpdateSpeciesAction;

class SpeciesController extends Controller
{
    // Create a new species
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:10|unique:species,code',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $species = Species::create($validator->validated());

        return response()->json([
            'message' => 'Species created successfully',
            'data'    => $species
        ], 201);
    }

    // Fetch all species
    public function index() 
    {
        $species = Species::withCount('trees')->get();

        return response()->json([
            'message' => 'Species fetched successfully',
            'data'    => $species
        ], 200);
    }


    public function update(Request $request, $id)
{
    $species = Species::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:10|unique:species,code,' . $id,
        'description' => 'nullable|string',
    ]);

    $dto = new SpeciesDTO($validated['name'], $validated['code'], $validated['description']);

    $updated = (new UpdateSpeciesAction())->handle($species, $dto);

    return response()->json([
        'message' => 'Species updated successfully',
        'data' => $updated,
    ], 200);
}

    // Delete a species
    public function destroy($id)
    {
        $species = Species::findOrFail($id);
        $species->delete();

        return response()->json([
            'message' => 'Species deleted successfully'
        ], 200);
    }
}
