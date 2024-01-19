<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Enum;

enum ServiceName: string
{
    case GOOGLE = 'google';

    /**
     * @return string
     */
    public function title(): string
    {
        return match ($this) {
            self::GOOGLE => 'Google',
        };
    }
}
