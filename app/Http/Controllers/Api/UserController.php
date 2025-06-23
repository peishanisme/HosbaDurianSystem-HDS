<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

}