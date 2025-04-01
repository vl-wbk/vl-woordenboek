<?php

declare(strict_types=1);

namespace App\States\Reporting;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Status: int implements HasLabel, HasColor, HasIcon
{
    case Open = 1;
    case InProgress = 2;
    case Closed = 3;

    public function getLabel(): string
    {
        $label =  match($this) {
            self::Open => 'onbehandeld',
            self::InProgress => 'in behandeling',
            self::Closed => 'behandeld',
        };

        return trans($label);
    }

    public function getIcon(): string
    {
        return match($this) {
            self::Open => 'tabler-circle-dashed-x',
            self::InProgress => 'tabler-circle-dashed',
            self::Closed => 'tabler-circle-dashed-check',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::Open => 'danger',
            self::InProgress => 'warning',
            self::Closed => 'success',
        };
    }
}
