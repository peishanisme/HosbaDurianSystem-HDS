<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Actions\UserManagement\CreateUserAction;
use App\Actions\UserManagement\UpdateUserAction;
use App\DataTransferObject\UserDTO;
use Illuminate\Validation\Rule;

class UserApiController extends Controller
{
    public function index()
    {
        return response()->json(User::with('roles')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone'     => ['required', 'string', Rule::unique('users', 'phone')],
            'role'      => ['required', Rule::exists('roles', 'id')],
            'is_active' => 'required|boolean',
        ]);

        app(CreateUserAction::class)->handle(UserDTO::fromArray($validated));

        return response()->json(['message' => 'User created successfully']);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'     => ['required', 'string', Rule::unique('users', 'phone')->ignore($user->id)],
            'role'      => ['required', Rule::exists('roles', 'id')],
            'is_active' => 'required|boolean',
        ]);

        app(UpdateUserAction::class)->handle($user, UserDTO::fromArray($validated));

        return response()->json(['message' => 'User updated successfully']);
    }

    public function show(User $user)
    {
        return response()->json($user->load('roles'));
    }
}