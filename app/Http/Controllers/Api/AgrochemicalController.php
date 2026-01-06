<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgrochemicalRecord;
use App\Models\Agrochemical;
use App\Models\AgrochemicalStockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Actions\AgrochemicalManagement\CreateAgrochemicalRecordAction;
use App\DataTransferObject\AgrochemicalRecordDTO;
use App\Actions\AgrochemicalManagement\UpdateAgrochemicalRecordAction;
use App\Actions\AgrochemicalManagement\CreateAgrochemicalStockAction;
use App\DataTransferObject\AgrochemicalStockMovementDTO;

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


   public function update(Request $request, $uuid)
    {
        // Find by UUID, not ID
        $agrochemical = AgrochemicalRecord::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'agrochemical_uuid' => 'required|exists:agrochemicals,uuid',
            'tree_uuid' => 'required|exists:trees,uuid',
            'applied_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $dto = new AgrochemicalRecordDTO(
            tree_uuid: $validated['tree_uuid'],
            agrochemical_uuid: $validated['agrochemical_uuid'],
            description: $validated['description'] ?? null,
            applied_at: $validated['applied_at'] ?? null,
        );

        $updated = (new UpdateAgrochemicalRecordAction())->handle($agrochemical, $dto);

        return response()->json([
            'message' => 'Agrochemical updated successfully',
            'data' => $updated,
        ], 200);
}


    // Delete a agrochemical
    public function destroy($uuid)
    {
        $agrochemical = AgrochemicalRecord::where('uuid', $uuid)->firstOrFail();
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

    public function getAllAgroRecords()
    {
        $records = AgrochemicalRecord::with(['agrochemical', 'tree'])->get();

        return response()->json([
            'message' => 'All agrochemical records fetched successfully',
            'data'    => $records
        ], 200);
    }

    /**
     * Get all trees that have a usage record for a specific agrochemical.
     */
    public function getTreesByAgrochemical($agrochemicalUuid)
    {
        $records = AgrochemicalRecord::where('agrochemical_uuid', $agrochemicalUuid)
            ->with(['tree', 'agrochemical'])
            ->orderByDesc('applied_at')
            ->get();

        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No trees found for this agrochemical',
                'data'    => [],
                'agrochemical_uuid' => $agrochemicalUuid,
            ], 200);
        }

        $trees = $records->groupBy('tree_uuid')->map(function ($group) {
            return [
                'tree'             => $group->first()->tree,
                'agrochemical'     => $group->first()->agrochemical,
                'usage_records'    => $group->values(),
                'usage_count'      => $group->count(),
                'latest_applied_at'=> $group->firstWhere('applied_at', '!=', null)?->applied_at,
            ];
        })->values();

        return response()->json([
            'message' => 'Trees with agrochemical usage fetched successfully',
            'data'    => $trees,
            'total'   => $trees->count(),
            'agrochemical_uuid' => $agrochemicalUuid,
        ], 200);
    }

    /**
     * Get all agrochemicals where remaining stock is greater than 0.
     */
    public function getAvailableStock()
    {
        $agrochemicals = Agrochemical::all();

        // Filter agrochemicals with stock > 0 and include stock info
        $available = $agrochemicals->filter(function ($agro) {
            return $agro->getRemainingStock() > 0;
        })->map(function ($agro) {
            return [
                'uuid'              => $agro->uuid,
                'name'              => $agro->name,
                'type'              => $agro->type,
                'price'             => $agro->price,
                'quantity_per_unit' => $agro->quantity_per_unit,
                'description'       => $agro->description,
                'thumbnail'         => $agro->thumbnail,
                'remaining_stock'   => $agro->getRemainingStock(),
                'latest_purchase'   => $agro->getLatestPurchaseDate(),
            ];
        })->values();

        return response()->json([
            'message' => 'Available agrochemicals (stock > 0) fetched successfully',
            'data'    => $available,
            'total'   => $available->count(),
        ], 200);
    }

    /**
     * Create an agrochemical stock movement (record stock in/out).
     * @param agrochemical_uuid - UUID of the agrochemical
     * @param movement_type - 'in' (purchase) or 'out' (usage/disposal)
     * @param quantity - quantity being added or removed
     * @param date - date of the movement
     * @param description - optional description of the movement
     */
    public function storeStockMovement(Request $request)
    {
        $validated = $request->validate([
            'agrochemical_uuid' => 'required|exists:agrochemicals,uuid',
            'movement_type'     => 'required|in:in,out',
            'quantity'          => 'required|integer|min:1',
            'date'              => 'required|date',
            'description'       => 'nullable|string',
        ]);

        try {
            // Create DTO from validated data
            $dto = new AgrochemicalStockMovementDTO(
                agrochemical_uuid: $validated['agrochemical_uuid'],
                movement_type: $validated['movement_type'],
                quantity: $validated['quantity'],
                date: $validated['date'],
                description: $validated['description'] ?? null,
            );

            // Use action to create the stock movement
            $stockMovement = (new CreateAgrochemicalStockAction())->handle($dto);

            return response()->json([
                'message' => 'Stock movement recorded successfully',
                'data'    => $stockMovement,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to record stock movement',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}