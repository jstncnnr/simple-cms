<?php

namespace App\Exceptions;

use InvalidArgumentException;

class PermissionDoesNotExist extends InvalidArgumentException
{
    public static function create(string $permissionName, ?string $guardName): static
    {
        return new static("There is no permission named `{$permissionName}` for guard `{$guardName}`.");
    }

    public static function withId(int|string $permissionId, ?string $guardName): static
    {
        return new static("There is no [permission] with ID `{$permissionId}` for guard `{$guardName}`.");
    }
}
