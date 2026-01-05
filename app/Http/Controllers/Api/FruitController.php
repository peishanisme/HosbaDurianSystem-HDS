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

    public function index()
    {
        $fruits = Fruit::with('tree.species')
            ->orderBy('harvested_at', 'desc')
            ->get();

        return response()->json($fruits);
    }

    public function update(Request $request, $uuid)
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

        $fruit = Fruit::where('uuid', $uuid)->firstOrFail();

        $fruit->update($validated);

        return response()->json([
            'message' => 'Fruit updated successfully',
            'data' => $fruit
        ], 200);
    }


    public function destroy($id)
    {
        $fruit = Fruit::where('uuid', $id)->firstOrFail();
        $fruit->delete();

        return response()->json([
            'message' => 'Fruit deleted successfully'
        ], 200);
    }

    /**
     * Get all fruits by harvest event UUID
     */
    public function getByHarvestEvent($harvestUuid)
    {
        try {
            $fruits = Fruit::with('tree.species')
                ->where('harvest_uuid', $harvestUuid)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Fruits fetched successfully',
                'data' => $fruits,
                'count' => $fruits->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching fruits',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
