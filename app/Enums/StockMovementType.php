<?php

declare(strict_types=1);

namespace App\Enums;

enum StockMovementType: string
{
    case In = 'in';
    case Out = 'out';

    public function label(): string
    {
        return match ($this) {
            self::In => 'In',
            self::Out => 'Out',
        };
    }

    public function sign(): int
    {
        return match ($this) {
            self::In => 1,
            self::Out => -1,
        };
    }
}
