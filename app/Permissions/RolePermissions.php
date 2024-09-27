<?php

namespace App\Permissions;

use App\Contracts\PermissionsGroup;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum RolePermissions: string implements HasLabel, PermissionsGroup
{
    case ViewAny = 'view_any';
    case Create = 'create';
    case Update = 'update';
    case View = 'view';
    case ForceDelete = 'force_delete';
    case ForceDeleteAny = 'force_delete_any';
    case Restore = 'restore';
    case Reorder = 'reorder';
    case Delete = 'delete';

    public function getLabel(): ?string
    {
        return Str::title(str_replace('_', ' ', $this->value));
    }

    public function getKey(): string
    {
        return self::getCategory() . ':' . $this->value;
    }

    public static function getCategory(): string
    {
        return 'role';
    }

    public static function getTabTitle(): string
    {
        return 'Role Permissions';
    }
}
