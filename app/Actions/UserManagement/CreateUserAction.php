<?php

namespace App\Actions\UserManagement;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\DataTransferObject\UserDTO;
use App\Actions\FormatPhoneNumberAction;

class CreateUserAction
{
    public function handle(UserDTO $dto): User
    {
        return DB::transaction(function () use ($dto) {
            $dto->phone = FormatPhoneNumberAction::handle($dto->phone);

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
