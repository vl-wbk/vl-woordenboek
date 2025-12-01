<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use BackedEnum;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ReferenceWorkType: int implements HasLabel, HasIcon
{
    case Unknown = 0;
    case Website = 1;
    case Journal = 2;
    case Dictionary = 3;
    case Newspaper = 4;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Unknown => 'Onbekend',
            self::Website => 'Website',
            self::Journal => 'Tijdschrift',
            self::Dictionary => 'Woordenboek',
            self::Newspaper => 'Krant'
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return null;
    }
}
