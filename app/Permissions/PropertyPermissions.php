<?php

namespace App\Permissions;

use App\Contracts\PermissionsGroup;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum PropertyPermissions: string implements HasLabel, PermissionsGroup
{
    case Create = 'create';
    case Edit = 'edit';
    case Remove = 'remove';

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
        return 'property';
    }

    public static function getTabTitle(): string
    {
        return 'Property Permissions';
    }
}
