<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;

enum FeedbackStatus: int implements HasLabel
{
    use Comparable;

    case Unprocessed = 0;
    case Processed = 1;

    public function getLabel(): string
    {
        return match($this) {
            self::Unprocessed => 'onbehandeld',
            self::Processed => 'behandeld',
        };
    }
}
