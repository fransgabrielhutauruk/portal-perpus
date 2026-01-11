<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = "Admin";
    case STAF = "Staf";

    public static function getAllRoles(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }
}
