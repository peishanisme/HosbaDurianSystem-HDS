<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Tree;
use App\Models\Label;
use App\Models\HarvestRecord;
use App\Models\TreeObservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\TreeGrowthLog;
use App\Actions\TreeManagement\UpdateTreeAction;
use App\DataTransferObject\TreeDTO;
use App\Actions\TreeManagement\UpdateTreeLocation;
use App\Actions\TreeManagement\AttachLabelToTreeAction;
use App\Actions\TreeManagement\DeleteTreeLabelAction;
use Illuminate\Support\Str;


class TreeController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'species_id'       => 'required|exists:species,id',
            'planted_at'       => 'nullable|date',
            'thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'flowering_period' => 'nullable|string',
            'height'           => 'nullable|numeric',
            'diameter'         => 'nullable|numeric',
            'area'             => 'nullable|string|in:A,B,C,D,E,F,G,H',
            'terrace'          => 'nullable|numeric',
            'water_valve'      => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $thumbnailUrl = null;

            // ✅ Handle image upload
            if ($request->hasFile('thumbnail')) {
                $ext = $request->file('thumbnail')->getClientOriginalExtension();

                // Generate a UUID-based filename
                $uuidName = Str::uuid()->toString() . '.' . $ext;

                // Save to Supabase bucket
                $request->file('thumbnail')->storeAs('trees/jpg', $uuidName, 'supabase');

                // Save relative path in DB
                $thumbnailUrl = "trees/jpg/{$uuidName}";
            }

            // ✅ Create Tree record
            $tree = Tree::create([
                'species_id'       => $request->species_id,
                'planted_at'       => $request->planted_at,
                'thumbnail'        => $thumbnailUrl,
                'latitude'         => $request->latitude,
                'longitude'        => $request->longitude,
                'flowering_period' => $request->flowering_period,
                'area'             => $request->area,
                
                'terrace'          => $request->terrace,
                'water_valve'      => $request->water_valve,
            ]);

            // ✅ Create growth log only when provided
            if ($request->has('height') || $request->has('diameter')) {
                $tree->growthLogs()->create([
                    'tree_id'   => $tree->id,
                    'tree_uuid' => $tree->uuid,
                    'height'    => $request->has('height') ? $request->height : null,
                    'diameter'  => $request->has('diameter') ? $request->diameter : null,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Tree created successfully with growth log',
                'data'    => $tree
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create tree',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function index(Request $request) {
        $query = Tree::with('species', 'labels');

        // Filter by area
        if ($request->has('area')) {
            $query->where('area', $request->area);
        }

        // Filter by terrace
        if ($request->has('terrace')) {
            $query->where('terrace', $request->terrace);
        }

        // Filter by water_valve
        if ($request->has('water_valve')) {
            $query->where('water_valve', $request->water_valve);
        }

        // Filter by species_id
        if ($request->has('species_id')) {
            $query->where('species_id', $request->species_id);
        }

        // flowering_status removed from `trees` table; use observations instead

        // Filter by flowering_period
        if ($request->has('flowering_period')) {
            $query->where('flowering_period', $request->flowering_period);
        }

        // Filter by tree_tag (search)
        if ($request->has('tree_tag')) {
            $query->where('tree_tag', 'like', '%' . $request->tree_tag . '%');
        }

        // Filter by planted_at date range
        if ($request->has('planted_at_from')) {
            $query->where('planted_at', '>=', $request->planted_at_from);
        }

        if ($request->has('planted_at_to')) {
            $query->where('planted_at', '<=', $request->planted_at_to);
        }

        // Filter by height range (using growth logs)
        if ($request->has('height_min') || $request->has('height_max')) {
            $query->whereHas('latestGrowthLog', function ($q) {
                if (request()->has('height_min')) {
                    $q->where('height', '>=', request()->height_min);
                }
                if (request()->has('height_max')) {
                    $q->where('height', '<=', request()->height_max);
                }
            });
        }

        // Filter by diameter range (using growth logs)
        if ($request->has('diameter_min') || $request->has('diameter_max')) {
            $query->whereHas('latestGrowthLog', function ($q) {
                if (request()->has('diameter_min')) {
                    $q->where('diameter', '>=', request()->diameter_min);
                }
                if (request()->has('diameter_max')) {
                    $q->where('diameter', '<=', request()->diameter_max);
                }
            });
        }

        // Filter by labels (can pass comma-separated label IDs or single ID)
        if ($request->has('label_ids')) {
            $labelIds = is_array($request->label_ids) 
                ? $request->label_ids 
                : explode(',', $request->label_ids);
            
            $query->whereHas('labels', function ($q) use ($labelIds) {
                $q->whereIn('labels.id', $labelIds);
            });
        }

        // Sort by field (default: created_at)
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $trees = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $trees
        ]);
    }

    public function show($id)
    {
        $tree = Tree::with('species')
            ->where('id', $id)
            ->first();

        if (!$tree) {
            return response()->json(['message' => 'Tree not found'], 404);
        }
        $latestGrowth = $tree->growthLogs()
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestGrowth) {
            $tree->height = $latestGrowth->height;
            $tree->width = $latestGrowth->diameter;
        } else {
            $tree->height = null;
            $tree->width = null;
        }

        return response()->json($tree);
    }

    public function update(Request $request, $id)
    {
        $tree = Tree::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'species_id' => 'required|exists:species,id',
        'planted_at' => 'nullable|date',
        'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        'flowering_period' => 'nullable|string',
        'height' => 'nullable|numeric',
        'diameter' => 'nullable|numeric',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'area' => 'nullable|string|in:A,B,C,D,E,F,G,H',
        'terrace' => 'nullable|integer',
        'water_valve' => 'nullable|integer',
    ]);

        DB::beginTransaction();
        try {
            $thumbnailUrl = $tree->thumbnail; // Keep existing thumbnail by default

            // ✅ If user uploads a new image, replace it
            if ($request->hasFile('thumbnail')) {
                $ext = $request->file('thumbnail')->getClientOriginalExtension();
                $uuidName = Str::uuid()->toString() . '.' . $ext;

                // Store new image
                $request->file('thumbnail')->storeAs('trees/jpg', $uuidName, 'supabase');
                $thumbnailUrl = "trees/jpg/{$uuidName}";
            }

            // Capture latest growth values before update
            $latestGrowth = $tree->growthLogs()->latest()->first();
            $prevHeight = $latestGrowth?->height;
            $prevDiameter = $latestGrowth?->diameter;

        $tree->update([
            'species_id'       => $request->species_id,
            'planted_at'       => $request->planted_at,
            'thumbnail'        => $thumbnailUrl,   // save the path in DB
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
            'flowering_period' => $request->flowering_period,
            'area'             => $request->area,
            
            'terrace'          => $request->terrace,
            'water_valve'      => $request->water_valve,
        ]);

        // If height or diameter provided, create/update growth log
        if ($request->has('height') || $request->has('diameter')) {
            $newHeight = $request->has('height') ? $request->height : $prevHeight;
            $newDiameter = $request->has('diameter') ? $request->diameter : $prevDiameter;

            $heightChanged = ($prevHeight === null && $request->has('height')) || ($request->has('height') && (float) $newHeight != (float) $prevHeight);
            $diameterChanged = ($prevDiameter === null && $request->has('diameter')) || ($request->has('diameter') && (float) $newDiameter != (float) $prevDiameter);

            if ($heightChanged || $diameterChanged) {
                if ($latestGrowth) {
                    // Update the latest growth log instead of creating a new one
                    $latestGrowth->update([
                        'height'   => $newHeight,
                        'diameter' => $newDiameter,
                    ]);
                } else {
                    // No previous growth log exists, create the first one
                    $tree->growthLogs()->create([
                        'tree_id'   => $tree->id,
                        'tree_uuid' => $tree->uuid,
                        'height'    => $newHeight,
                        'diameter'  => $newDiameter,
                    ]);
                }
            }
        }

            DB::commit();

            return response()->json([
                'message' => 'Tree updated successfully',
                'data' => $tree
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update tree',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id) {
        // Accept either numeric ID or UUID in the {id} parameter
        if (preg_match('/^[0-9]+$/', $id)) {
            $tree = Tree::findOrFail((int) $id);
        } else {
            $tree = Tree::where('uuid', $id)->firstOrFail();
        }

        DB::beginTransaction();

        try {
            $tree->growthLogs()->delete();
            $tree->delete();

            DB::commit();

            return response()->json([
                'message' => 'Tree deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete tree',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showByUuid($uuid)
    {
        $tree = Tree::with('species')->where('uuid', $uuid)->first();

        if (!$tree) {
            return response()->json(['message' => 'Tree not found'], 404);
        }

        $latestGrowth = $tree->growthLogs()->latest()->first();
        $tree->height = $latestGrowth?->height;
        $tree->width = $latestGrowth?->diameter;

        return response()->json($tree);
    }

    public function getTreeTagList()
    {
        $trees = Tree::with('species')
            ->orderBy('created_at', 'desc')
            ->get();

        $treeTags = $trees->map(function ($tree) {
            return [
                'id' => $tree->id,
                'uuid' => $tree->uuid,
                'tree_tag' => $tree->tree_tag, // Add this line
                'latitude' => $tree->latitude,
                'longitude' => $tree->longitude,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $treeTags
        ]);
    }

    public function updateTreeLocation(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tree = Tree::where('uuid', $id)->firstOrFail();

        try {
            $tree = (new UpdateTreeLocation())->execute($tree, $request->latitude, $request->longitude);

            return response()->json([
                'message' => 'Tree location updated successfully',
                'data' => $tree
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update tree location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFloweringPeriod($uuid)
    {
        try {
            $tree = Tree::where('uuid', $uuid)->firstOrFail();
            $floweringPeriod = $tree->getFloweringPeriod();

            return response()->json([
                'success' => true,
                'data' => [
                    'uuid' => $tree->uuid,
                    'tree_tag' => $tree->tree_tag,
                    'flowering_period' => $floweringPeriod
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tree not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function attachLabel(Request $request, $id)
    {
        $tree = Tree::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'color' => 'nullable|string|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = (new AttachLabelToTreeAction())->execute(
                $tree,
                $request->label,
                $request->color
            );

            return response()->json([
                'message' => $result['message'],
                'data' => [
                    'tree_id' => $tree->id,
                    'label' => $result['label'],
                    'is_new_label' => $result['is_new_label'],
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to attach label',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getLabels($id)
    {
        $tree = Tree::findOrFail($id);

        $labels = $tree->labels()->get();

        return response()->json([
            'success' => true,
            'data' => [
                'tree_id' => $tree->id,
                'labels' => $labels
            ]
        ]);
    }

    public function getAllLabels()
    {
        $labels = Label::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $labels
        ]);
    }

    public function removeTreeLabel($treeId, $labelId)
    {
        $tree = Tree::findOrFail($treeId);
        $label = Label::findOrFail($labelId);

        try {
            $result = (new DeleteTreeLabelAction())->execute($tree, $label);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'label_deleted' => $result['label_deleted'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove label from tree',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTreesByLabel($labelId)
    {
        $label = Label::findOrFail($labelId);

        $trees = $label->trees()
                    ->with('species')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'label_id' => $label->id,
                'label_name' => $label->label,
                'label_color' => $label->color,
                'trees_count' => $trees->count(),
                'trees' => $trees
            ]
        ]);
    }

    

    public function createObservation(Request $request, $id)
    {
        $tree = Tree::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'harvest_uuid' => 'required|uuid',
            'flowering_status' => 'required|string|in:A,B,C,D,X',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $exists = TreeObservation::where('tree_uuid', $tree->uuid)
                ->where('harvest_uuid', $request->harvest_uuid)
                ->exists();

            $observation = (new \App\Actions\TreeManagement\CreateTreeObservationAction())->execute(
                $tree,
                $request->harvest_uuid,
                $request->flowering_status
            );

            if ($exists) {
                return response()->json([
                    'message' => 'Observation updated',
                    'data' => $observation
                ], 200);
            }

            return response()->json([
                'message' => 'Observation created',
                'data' => $observation
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save observation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFloweringStatus(Request $request, $id)
    {
        $tree = Tree::findOrFail($id);

        // If client requests history, return all observations
        if ($request->boolean('history')) {
            $observations = $tree->observations()
                ->orderBy('created_at', 'desc')
                ->get(['harvest_uuid', 'flowering_status', 'created_at']);

            return response()->json([
                'success' => true,
                'data' => [
                    'tree_id' => $tree->id,
                    'observations' => $observations,
                ]
            ]);
        }

        $latest = $tree->observations()->orderBy('created_at', 'desc')->first();

        return response()->json([
            'success' => true,
            'data' => [
                'tree_id' => $tree->id,
                'flowering_status' => $latest?->flowering_status,
                'harvest_uuid' => $latest?->harvest_uuid,
                'observed_at' => $latest?->created_at,
            ]
        ]);
    }

    public function createHarvestRecord(Request $request, $id)
    {
        $tree = Tree::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'harvest_uuid' => 'nullable|uuid',
            'harvest_date' => 'nullable|date',
            'num_of_fruits' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'spoilt' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $harvestUuid = $request->harvest_uuid ?? null;

            if ($harvestUuid && HarvestRecord::where('harvest_uuid', $harvestUuid)->exists()) {
                $existing = HarvestRecord::where('harvest_uuid', $harvestUuid)->first();
                return response()->json([
                    'message' => 'Harvest record already exists',
                    'data' => $existing,
                ], 200);
            }

            $record = HarvestRecord::create([
                'harvest_uuid' => $harvestUuid,
                'tree_uuid' => $tree->uuid,
                'harvest_date' => $request->harvest_date ?? null,
                'num_of_fruits' => $request->num_of_fruits ?? 0,
                'weight' => $request->weight ?? null,
                'spoilt' => $request->spoilt ?? false,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Harvest record created',
                'data' => $record,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create harvest record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getHarvestRecords(Request $request, $id)
    {
        $tree = Tree::findOrFail($id);

        // Return rows from harvest_records table for this tree
        $query = HarvestRecord::where('tree_uuid', $tree->uuid)->orderBy('harvest_date', 'desc');

        if ($request->has('harvest_uuid')) {
            $query->where('harvest_uuid', $request->harvest_uuid);
        }

        if ($request->has('from')) {
            $query->where('harvest_date', '>=', $request->from);
        }
        if ($request->has('to')) {
            $query->where('harvest_date', '<=', $request->to);
        }

        $perPage = (int) $request->get('per_page', 20);
        $records = $query->paginate($perPage);

        // Return harvest records array and pagination metadata separately
        return response()->json([
            'success' => true,
            'data' => [
                'tree_id' => $tree->id,
                'tree_uuid' => $tree->uuid,
                'harvest_records' => $records->items(),
                'pagination' => [
                    'current_page' => $records->currentPage(),
                    'per_page' => $records->perPage(),
                    'total' => $records->total(),
                    'last_page' => $records->lastPage(),
                ],
            ]
        ]);
    }

}





