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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = $this->permissions();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission['name'],
                'guard_name' => 'web',
            ]);
        }

        //super-admin
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super-Admin',
        ]);
        $superAdmin->givePermissionTo(Permission::all());

        //manager
        $manager = Role::firstOrCreate([
            'name' => 'Manager',
        ]);
        $manager->givePermissionTo(Permission::whereNotIn('name', [
            'create-user',
            'edit-user',
            'edit-permissions',
        ])->get());

        //worker
        $worker = Role::firstOrCreate([
            'name' => 'Worker',
        ]);
        $worker->givePermissionTo(Permission::whereIn('name', [
            'view-tree',
            'view-health-record',
            'view-fertilizer-pesticide',
            'view-fertilization-activity',
            'view-harvest-event',
        ])->get());

        
    }

    public function permissions(): array
    {
        return [
            //user management
            ['name' => 'view-user'],
            ['name' => 'create-user'],
            ['name' => 'edit-user'],

            //manage activity log
            ['name' => 'view-activity-log'],

            //manage roles and permissions
            ['name' => 'view-roles'],
            ['name' => 'edit-permissions'],
            ['name' => 'view-permissions'],

            //manage trees
            ['name' => 'create-tree'],
            ['name' => 'edit-tree'],
            ['name' => 'view-tree'],
            ['name' => 'delete-tree'],

            //manage health records
            ['name' => 'create-health-record'],
            ['name' => 'edit-health-record'],
            ['name' => 'view-health-record'],
            ['name' => 'delete-health-record'],

            //manage fertilizer and pesticide inventory
            ['name' => 'create-fertilizer-pesticide'],
            ['name' => 'edit-fertilizer-pesticide'],
            ['name' => 'view-fertilizer-pesticide'],
            ['name' => 'delete-fertilizer-pesticide'],
            ['name' => 'update-stock-levels'],

            //record fertilization and pesticide application activity
            ['name' => 'create-fertilization-activity'],
            ['name' => 'edit-fertilization-activity'],
            ['name' => 'view-fertilization-activity'],
            ['name' => 'delete-fertilization-activity'],

            //manage harvest events
            ['name' => 'create-harvest-event'],
            ['name' => 'edit-harvest-event'],
            ['name' => 'view-harvest-event'],
            ['name' => 'delete-harvest-event'],
            ['name' => 'close-harvest-event'],

            //manage sellable harvested fruit
            ['name' => 'create-fruit'],

            //manage buyers
            ['name' => 'create-buyer'],
            ['name' => 'edit-buyer'],
            ['name' => 'view-buyer'],
            ['name' => 'delete-buyer'],

            //manage sales
            ['name' => 'create-sale'],
            ['name' => 'edit-sale'],
            ['name' => 'view-sale'],
            ['name' => 'delete-sale'],

            //manage reports
            ['name' => 'view-reports'],
            ['name' => 'export-reports'],
                      
        ];
    }
}
