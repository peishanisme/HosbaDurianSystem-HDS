<?php

namespace App\Actions\UserManagement;

use App\DataTransferObject\UserDTO;
use App\DataTransferObjects\AdminDTO;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CreateUserAction
{
    public function handle(UserDTO $dto): User
    {
        return DB::transaction(function () use ($dto) {

            $user = User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => 'hosbadurian',
                'phone' => $dto->phone,
                'is_active' => $dto->is_active,
            ]);

            $role = Role::find($dto->role)->name;
            $user->assignRole($role);

            return $user;
        });
    }
}
