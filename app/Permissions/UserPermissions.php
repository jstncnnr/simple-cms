<?php

namespace App\Permissions;

use App\Contracts\PermissionsGroup;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum UserPermissions: string implements HasLabel, PermissionsGroup
{
    case ViewAll = 'view_all';
    case Invite = 'invite';
    case Update = 'update';
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
        return 'user';
    }

    public static function getTabTitle(): string
    {
        return 'User Permissions';
    }
}
