<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum FeedbackTrueFalse: string implements HasLabel, HasColor, HasIcon
{
    case true =  'Ja';
    case false = 'Nee';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getColor(): string
    {
        return match($this) {
            self::true => 'success',
            self::false => 'danger'
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::true => 'heroicon-o-check-circle',
            self::false => 'heroicon-o-x-circle'
        };
    }
}
