<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Status: int implements HasLabel, HasColor
{
    case Draft = 0;
    case Published = 1;
    case Archived = 2;

    public function getLabel(): string
    {
        $label = match($this) {
            self::Draft => 'Klad versie',
            self::Published => 'Gepubliceerd',
            self::Archived => 'gearchiveerd',
        };

        return trans($label);
    }

    public function getColor(): string
    {
        return match($this) {
            self::Draft => 'warning',
            self::Published => 'success',
            self::Archived => 'info',
        };
    }
}
