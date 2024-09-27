<?php

namespace Database\Seeders;

use App\Contracts\PermissionsGroup;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Permissions\PropertyPermissions;
use App\Permissions\RolePermissions;
use App\Permissions\UserPermissions;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createPermissions(UserPermissions::class);
        $this->createPermissions(RolePermissions::class);
        $this->createPermissions(PropertyPermissions::class);
    }

    private function createPermissions(string $class): void
    {
        if(! is_a($class, \BackedEnum::class, true))
            return;

        if(! is_a($class, PermissionsGroup::class, true))
            return;

        foreach($class::cases() as $permission) {
            Permission::firstOrCreate(['name' => $class::getCategory() . ':' . $permission->value]);
        }
    }
}
