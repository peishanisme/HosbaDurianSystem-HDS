<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TreeController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'species_id' => 'required|exists:species,id',
            'planted_at' => 'required|date',
            'thumbnail' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'flowering_period' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tree = Tree::create([
            'species_id' => $request->species_id,
            'planted_at' => $request->planted_at,
            'thumbnail' => $request->thumbnail,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'flowering_period' => $request->flowering_period,
        ]);

        return response()->json([
            'message' => 'Tree created successfully',
            'data' => $tree
        ], 201);
    }
}