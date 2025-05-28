<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum FeedbackTrueFalse: string implements HasLabel, HasColor
{
    case true =  'Ja';
    case false = 'Nee';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::true => 'success',
            self::false => 'danger'
        };
    }
}
