<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = "Admin";
    case SUPER_ADMIN = "Super Admin";

    public static function getAllRoles(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }
}
