<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\TreeGrowthLog;
use App\Actions\TreeManagement\UpdateTreeAction;
use App\DataTransferObject\TreeDTO;
use App\Actions\TreeManagement\UpdateTreeLocation;
use Illuminate\Support\Str;


class TreeController extends Controller
{
   public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'species_id'       => 'required|exists:species,id',
            'planted_at'       => 'required|date',
            'thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'flowering_period' => 'nullable|string',
            'height'           => 'required|numeric',
            'diameter'         => 'required|numeric',
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
                'thumbnail'        => $thumbnailUrl,   // save the path in DB
                'latitude'         => $request->latitude,
                'longitude'        => $request->longitude,
                'flowering_period' => $request->flowering_period,
            ]);

            // ✅ Create growth log
            $tree->growthLogs()->create([
                'tree_id'   => $tree->id,
                'tree_uuid' => $tree->uuid,
                'height'    => $request->height,
                'diameter'  => $request->diameter,
            ]);

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


    public function index() {
        $trees = Tree::with('species')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $trees
        ]);
    }

    public function show($id) {
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

    public function update(Request $request, $id) {
    $tree = Tree::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'species_id' => 'required|exists:species,id',
        'planted_at' => 'required|date',
        'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        'flowering_period' => 'nullable|string',
        'height' => 'required|numeric',
        'diameter' => 'required|numeric',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    DB::beginTransaction();
    try{
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
        ]);

        // If height or diameter changed, create a new growth log entry
        // (treat null previous as a change so first log is recorded)
        $newHeight = $request->height;
        $newDiameter = $request->diameter;

        $heightChanged = $prevHeight === null || (float) $newHeight != (float) $prevHeight;
        $diameterChanged = $prevDiameter === null || (float) $newDiameter != (float) $prevDiameter;

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
        $tree = Tree::findOrFail($id);

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

    public function showByUuid($uuid){
        $tree = Tree::with('species')->where('uuid', $uuid)->first();

        if (!$tree) {
            return response()->json(['message' => 'Tree not found'], 404);
        }

        $latestGrowth = $tree->growthLogs()->latest()->first();
        $tree->height = $latestGrowth?->height;
        $tree->width = $latestGrowth?->diameter;

        return response()->json($tree);
    }

    public function getTreeTagList() {
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

    public function updateTreeLocation(Request $request, $id) {
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

}