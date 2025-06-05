<?php

namespace App\Actions\UserManagement;

use App\DataTransferObject\UserDTO;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UpdateUserAction
{
    public function handle(User $user, UserDTO $dto): User
    {
         return DB::transaction(function () use ($user, $dto) {
            $user->update([
                'name'         => $dto->name,
                'email'        => $dto->email,
                'phone'        => $dto->phone,
                'is_active'    => $dto->is_active,
            ]);
            
            $role = Role::find($dto->role)->name;
            return tap($user)->syncRoles($role);
            
        });
    }
}
