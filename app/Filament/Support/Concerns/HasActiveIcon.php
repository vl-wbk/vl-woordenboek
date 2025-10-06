<?php

declare(strict_types=1);

namespace App\Filament\Support\Concerns;

trait HasActiveIcon
{
    public static function getActiveNavigationIcon(): string
    {
        /** @phpstan-ignore-next-line */
        return str(self::getNavigationIcon())
            ->replace('heroicon-o-', 'heroicon-s-')
            ->toString();
    }
}
