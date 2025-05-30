<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //super-admin
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super-Admin',
        ]);
        // $superAdmin->givePermissionTo(Permission::all());

        //manager
        $manager = Role::firstOrCreate([
            'name' => 'Manager',
        ]);
        // $admin->givePermissionTo(Permission::whereNotIn('name', [
        //     'view-roles-permissions',
        //     'edit-roles-permissions',
        //     'view-system-logs',
        //     'export-event-vendor',
        //     'export-transaction',
        //     'export-redemption',
        //     'export-pwp-record',
        //     'export-check-in'
        // ])->get());

        //worker
        $worker = Role::firstOrCreate([
            'name' => 'Worker',
        ]);
        // $staff->givePermissionTo([
        //     'view-member',
        //     'create-member',
        //     'check-in-user',
        //     'create-transaction',
        //     'edit-transaction',
        //     'create-redemption',
        //     'view-redemption',
        //     'view-transaction',
        //     'view-pwp-record'
        // ]);
    }
}
