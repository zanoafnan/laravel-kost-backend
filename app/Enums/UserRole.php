<?php

namespace App\Enums;

enum UserRole: string
{
    case OWNER = 'OWNER';
    case REGULAR = 'REGULAR';
    case PREMIUM = 'PREMIUM';

    public function label(): string
    {
        return match ($this) {
            self::OWNER => 'Owner',
            self::REGULAR => 'Regular User',
            self::PREMIUM => 'Premium User',
        };
    }
}