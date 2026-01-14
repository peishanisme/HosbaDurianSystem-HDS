<?php

namespace App\Actions\UserManagement;

use App\DataTransferObject\UserDTO;
use App\Traits\PhoneNumberTrait;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UpdateUserAction
{
    use PhoneNumberTrait;
    public function handle(User $user, UserDTO $dto): User
    {
        $dto->phone = self::formatForStorage($dto->phone);

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
