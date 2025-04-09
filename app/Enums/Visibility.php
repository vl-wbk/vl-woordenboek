<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Visibility: int implements HasLabel, HasColor, HasIcon
{
    case Intern = 0;
    case Public = 1;

    public function getColor(): string
    {
        return match($this) {
            self::Intern => 'danger',
            self::Public => 'success',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::Intern => 'heroicon-o-eye-slash',
            self::Public => 'heroicon-o-eye',
        };
    }

    public function getLabel(): string
    {
        return match($this) {
            self::Intern => 'Intern zichtbaar',
            self::Public => 'Publiekelijk zichtbaar',
        };
    }
}
