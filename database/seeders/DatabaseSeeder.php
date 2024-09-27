<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $role = Role::create(['name' => 'Global Administrator']);
        $role->permissions()->attach(Permission::all());

        $user = User::create(['name' => 'Administrator', 'email' => 'admin@example.com', 'password' => 'password']);
        $role->users()->attach($user);
    }
}
