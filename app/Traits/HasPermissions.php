<?php

namespace App\Traits;

use App\Exceptions\PermissionDoesNotExist;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->morphToMany(
            Permission::class,
            'model',
            'model_has_permissions',
            'model_morph_key',
            'permission_id'
        );
    }

    /**
     * @throws PermissionDoesNotExist
     */
    public function filterPermission(string|int|Permission|\BackedEnum $permission)
    {
        if($permission instanceof \BackedEnum) {
            $permission = $permission->value;
        }

        if(is_int($permission)) {
            $permission = Permission::query()->find($permission);
        }

        if(is_string($permission)) {
            $permission = Permission::query()->where('name', $permission)->get()->first();
        }

        if(!$permission instanceof Permission) {
            throw new PermissionDoesNotExist;
        }

        return $permission;
    }

    public function checkPermissionTo(string|int|Permission|\BackedEnum $permission): bool
    {
        try {
            return $this->hasPermissionTo($permission);
        } catch (PermissionDoesNotExist $e) {
            return false;
        }
    }

    public function hasPermissionTo(string|Permission|\BackedEnum $permission): bool
    {
        $permission = $this->filterPermission($permission);
        return $this->hasDirectPermission($permission) || $this->hasPermissionViaRole($permission);
    }

    public function hasDirectPermission(string|Permission|\BackedEnum $permission): bool
    {
        $permission = $this->filterPermission($permission);
        return $this->permissions->contains($permission->getKeyName(), $permission->getKey());
    }

    protected function hasPermissionViaRole(Permission $permission): bool
    {
        if (is_a($this, Role::class)) {
            return false;
        }

        return $this->hasRole($permission->roles);
    }
}
