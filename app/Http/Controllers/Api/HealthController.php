<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\DataTransferObject\HealthRecordDTO;
use Illuminate\Support\Str;
use App\Actions\TreeManagement\Health\UpdateHealthAction;

class HealthController extends Controller
{
   public function store(Request $request)
{
    $validated = $request->validate([
        'tree_uuid'   => 'required|exists:trees,uuid',
        'disease_id'  => 'required|exists:diseases,id',
        'status'      => 'required|string|max:255',
        'recorded_at' => 'nullable|date',
        'treatment'   => 'nullable|string',
        'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    DB::beginTransaction();

    try {
        $thumbnailUrl = null;

        // ✅ Handle image upload (if exists)
        if ($request->hasFile('thumbnail')) {
            $ext = $request->file('thumbnail')->getClientOriginalExtension();

            // Generate UUID-based filename
            $uuidName = Str::uuid()->toString() . '.' . $ext;

            // ✅ Make sure folder name matches actual bucket path
            // e.g. if your bucket is "health-record", and inside it you want "jpg/"
            $request->file('thumbnail')->storeAs('health-record/jpg', $uuidName, 'supabase');

            // ✅ Save relative path for frontend to reconstruct public URL
            $thumbnailUrl = "health-record/jpg/{$uuidName}";
        }

        // ✅ Create record
        $record = HealthRecord::create([
            'id'         => Str::uuid(),
            ...$validated,
            'thumbnail'  => $thumbnailUrl,
        ]);

        DB::commit();

        return response()->json([
            'message' => 'Health record created successfully',
            'data'    => $record,
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to create health record',
            'error'   => $e->getMessage(),
        ], 500);
    }
}

    public function index() 
    {
        $records = HealthRecord::all();

        return response()->json([
            'message' => 'Health records fetched successfully',
            'data'    => $records
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $healthRecord = HealthRecord::findOrFail($id);

        $validated = $request->validate([
            'tree_uuid'   => 'required|exists:trees,uuid',
            'disease_id'  => 'required|exists:diseases,id',
            'status'      => 'required|string|max:255',
            'recorded_at' => 'nullable|date',
            'treatment'   => 'nullable|string',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $thumbnailUrl = $healthRecord->thumbnail; 

            // ✅ If user uploads a new image, replace it
            if ($request->hasFile('thumbnail')) {
                $ext = $request->file('thumbnail')->getClientOriginalExtension();
                $uuidName = Str::uuid()->toString() . '.' . $ext;

                // Store new image
                $request->file('thumbnail')->storeAs('health-record/jpg', $uuidName, 'supabase');
                $thumbnailUrl = "health-record/jpg/{$uuidName}";
            }

            // ✅ Update the record
            $healthRecord->update([
                'tree_uuid'   => $validated['tree_uuid'],
                'disease_id'  => $validated['disease_id'],
                'status'      => $validated['status'],
                'recorded_at' => $validated['recorded_at'] ?? null,
                'treatment'   => $validated['treatment'] ?? null,
                'thumbnail'   => $thumbnailUrl,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Health record updated successfully',
                'data'    => $healthRecord,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update health record',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($uuid)
    {
        $healthRecord = HealthRecord::findOrFail($uuid);
        $healthRecord->delete();

        return response()->json([
            'message' => 'Health record deleted successfully'
        ], 200);
    }

   public function getByTree($uuid)
    {
        $records = HealthRecord::whereHas('tree', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })
        ->with('disease')
        ->orderBy('recorded_at', 'desc') // ✅ Newest first
        ->get();

        return response()->json([
            'message' => 'Health records for tree fetched successfully',
            'data'    => $records
        ], 200);
    }
}
