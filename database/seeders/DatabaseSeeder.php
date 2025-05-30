<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);
        User::create([
            "name" => "Super Admin",
            "email" => "admin@hosbadurian.com",
            "phone" => "0123456789",
            "password" => bcrypt("hosbadurian"),
        ])->assignRole("Super-Admin");
       
    }
}
