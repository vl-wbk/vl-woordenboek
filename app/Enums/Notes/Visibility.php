<?php

declare(strict_types=1);

namespace App\Enums\Notes;

use BackedEnum;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum Visibility: int implements HasLabel, HasColor, HasIcon
{
    case Public = 0;
    case Editors = 2;
    case EditorInChief = 3;

    public function getLabel(): string
    {
        return match($this) {
            self::Public => 'Iedereen',
            self::Editors => 'Redacteurs',
            self::EditorInChief => 'Eindredacteurs',
        };
    }

    public function getColor(): array
    {
        return Color::Blue;
    }

    public function getIcon(): BackedEnum
    {
        return Heroicon::Eye;
    }
}
