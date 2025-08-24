<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuyerController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name'    => 'required|string|max:255',
            'contact_name'    => 'required|string|max:255',
            'contact_number'  => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255|unique:buyers,email',
            'address'         => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $buyer = Buyer::create($request->only([
            'company_name',
            'contact_name',
            'contact_number',
            'email',
            'address',
        ]));

        return response()->json([
            'message' => 'Buyer created successfully.',
            'data' => $buyer
        ], 201);
    }

    public function index()
    {
        $buyers = Buyer::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Buyers fetched successfully',
            'data' => $buyers,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $buyer = Buyer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'company_name'    => 'sometimes|required|string|max:255',
            'contact_name'    => 'sometimes|required|string|max:255',
            'contact_number'  => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255|unique:buyers,email,' . $buyer->id,
            'address'         => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $buyer->update($request->only([
            'company_name',
            'contact_name',
            'contact_number',
            'email',
            'address',
        ]));

        return response()->json([
            'message' => 'Buyer updated successfully.',
            'data' => $buyer
        ], 200);
    }

    public function destroy($id)
    {
        $buyer = Buyer::findOrFail($id);
        $buyer->delete();

        return response()->json([
            'message' => 'Buyer deleted successfully.'
        ], 200);
    }

    public function show($id)
    {
        $buyer = Buyer::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $buyer
        ], 200);
    }
}
