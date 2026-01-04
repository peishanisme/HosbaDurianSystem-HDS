<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Services\ForgotPasswordService;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();

        return response()->json($users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'roles' => $user->getRoleNames(),
                'is_active' => $user->is_active,
            ];
        }));
}   

    // Show a single user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Create a new user
    public function store(Request $request)
    {   
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'is_active' => 'boolean',
            'role_id' => 'required|integer',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        DB::table('model_has_roles')->insert([
        'role_id' => $validated['role_id'],
        'model_type' => User::class,
        'model_id' => $user->id,
    ]);

        return response()->json($user, 201);
    }

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name'       => 'sometimes|required|string|max:255',
        'email'      => "sometimes|nullable|email|unique:users,email,{$id}",
        'phone'      => 'nullable|string|max:20',
        'password'   => 'nullable|string|min:6',
        'is_active'  => 'boolean',
        'role_id'       => 'sometimes|integer|exists:roles,id', // validate role ID
    ]);

    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    // If role is provided, update the user's role
    if ($request->has('role')) {
        $role = Role::find($request->input('role'));
        if ($role) {
            $user->syncRoles($role->name); // Use role name with Spatie
        }
    }

    return response()->json($user);
}

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function checkPhone($phone)
    {
        try {
            // Trim whitespace or special chars
            $phone = trim($phone);

            // Check if user exists and get user data
            $user = User::where('phone', $phone)->first();

            if ($user) {
                // Phone is valid, send OTP automatically
                (new ForgotPasswordService())->sendOtp($phone);

                return response()->json([
                    'exists' => true,
                    'message' => 'Phone number is registered. OTP sent to your email.',
                    'email' => $user->email,
                    'otp_sent' => true,
                ]);
            }

            return response()->json([
                'exists' => false,
                'message' => 'Phone number not found',
                'email' => null,
                'otp_sent' => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error checking phone number',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify OTP for password reset
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|string|max:20',
                'otp' => 'required|string|size:6',
            ]);

            $phone = trim($validated['phone']);
            $otp = $validated['otp'];

            // Verify OTP using ForgotPasswordService
            $isValid = (new ForgotPasswordService())->verifyOtp($phone, $otp);

            if ($isValid) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP verified successfully. You can now reset your password.',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error verifying OTP',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset password after OTP verification
     */
    public function resetPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|string|max:20',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            $phone = trim($validated['phone']);
            $newPassword = $validated['new_password'];

            // Reset password using ForgotPasswordService
            (new ForgotPasswordService())->resetPassword($phone, $newPassword);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully. You can now login with your new password.',
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error resetting password',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}