<?php

namespace App\Contracts;

interface PermissionsGroup
{
    public static function getCategory(): string;
    public static function getTabTitle(): string;

    public function getKey(): string;
}
