<?php

namespace App\Enums\Pro;

enum AccountStatus: string
{
    case Active = 'active';

    case Inactive = 'inactive';

    case Suspended = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Suspended => 'Suspended',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Inactive => 'gray',
            self::Suspended => 'red',
        };
    }
}
