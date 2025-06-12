<?php

namespace App\Actions\UserManagement;

use Spatie\Permission\Models\Role;

class UpdatePermissionAction
{
    public static function handle(Role $role, array $selectedPermissions): Role
    {
        return $role->syncPermissions($selectedPermissions);
    }
}
