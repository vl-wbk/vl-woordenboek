<?php

namespace App;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserTypes: string implements HasLabel, HasColor, HasIcon
{
    use Comparable;

    case Developer = 'ontwikkelaars';
    case Administrators = 'Administrators';
    case Volunteers = 'Vrijwilligers';
    case Normal = 'Normale gebruiker';

    public function getLabel(): ?string
    {
        return ucfirst($this->value);
    }

    public function getIcon(): ?string
    {
        return match($this) {
            self::Developer => 'heroicon-o-code-bracket',
            self::Administrators => 'heroicon-o-key',
            self::Volunteers => 'heroicon-o-users',
            self::Normal => 'heroicon-o-user-circle',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Developer, self::Administrators => 'danger',
            self::Volunteers => 'info',
            self::Normal => 'success',
        };
    }
}
