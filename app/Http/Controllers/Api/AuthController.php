<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = $request->user();

        // Only allow users with role id 1 (super admin) or 3 (worker)
        $roleIds = $user->roles()->pluck('id')->toArray();

        if (!in_array(1, $roleIds, true) && !in_array(3, $roleIds, true)) {
            return response()->json([
                'message' => 'You do not have permission to access this application.'
            ], 403);
        }

        // Create token (Sanctum)
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }
}