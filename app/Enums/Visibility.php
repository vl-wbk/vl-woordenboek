<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum Visibility: int implements HasIcon, HasColor
{
    use Comparable;

    case Visible = 1;
    case Hidden = 0;

    public function getIcon(): string
    {
        return match ($this) {
            self::Visible => 'heroicon-o-eye',
            self::Hidden => 'heroicon-o-eye-slash',
        };
    }



    public function getColor(): string
    {
        return match($this) {
            self::Hidden => 'danger',
            self::Visible => 'success',
        };
    }

    public function isHidden(): bool
    {
        return $this->is(self::Hidden);
    }

    public function isVisible(): bool
    {
        return $this->is(self::Visible);
    }
}
