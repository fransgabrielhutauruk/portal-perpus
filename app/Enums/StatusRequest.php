<?php

namespace App\Enums;

enum StatusRequest: int
{
    case MENUNGGU = 0;
    case DISETUJUI = 1;
    case DITOLAK = -1;

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU => 0,
            self::DISETUJUI => 1,
            self::DITOLAK => -1,
        };
    }
}
