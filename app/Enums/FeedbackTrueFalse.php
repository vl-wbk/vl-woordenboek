<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum FeedbackTrueFalse: string implements HasLabel
{
    case true =  'correct';
    case false = 'incorrect';

    public function getLabel(): string
    {
        return match ($this) {
            self::true => 'Ja, dit klopt',
            self::false => 'Nee, dit klopt niet',
        };
    }
}
