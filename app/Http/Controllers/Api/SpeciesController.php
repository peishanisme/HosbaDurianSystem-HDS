<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Species;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpeciesController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:10|unique:species,code',
            'description' => 'nullable|string',
            'is_active'   => 'required|boolean',
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

    public function index() 
    {
        $species = Species::all();
        return response()->json([
            'message' => 'Species fetched successfully',
            'data'    => $species
        ], 200);
    }

}
