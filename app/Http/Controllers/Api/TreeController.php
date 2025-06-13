<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\TreeGrowthLog;


class TreeController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'species_id' => 'required|exists:species,id',
            'planted_at' => 'required|date',
            'thumbnail' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'flowering_period' => 'nullable|string',
            'height' => 'required|numeric',
            'diameter' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $tree = Tree::create([
                'species_id' => $request->species_id,
                'planted_at' => $request->planted_at,
                'thumbnail' => $request->thumbnail,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'flowering_period' => $request->flowering_period,
            ]);

            $tree->growthLogs()->create([
                'height' => $request->height,
                'diameter' => $request->diameter,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Tree created successfully with growth log',
                'data' => $tree
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create tree',
                'error' => $e->getMessage()
            ], 500);
        }
}

    public function index() {
        $trees = Tree::with('species')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $trees
        ]);
    }

    public function show($uuid) {
        $tree = Tree::with('species')
            ->where('uuid', $uuid)
            ->first();

        if (!$tree) {
            return response()->json(['message' => 'Tree not found'], 404);
        }

        return response()->json($tree);
    }

}