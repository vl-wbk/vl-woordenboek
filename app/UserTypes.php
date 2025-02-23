<?php

namespace App;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserTypes: int implements HasLabel, HasColor, HasIcon
{
    use Comparable;

    case Normal = 0;
    case Editor = 1;
    case EditorInChief = 2;
    case Developer = 3;
    case Administrators = 4;

    public function getLabel(): ?string
    {
        $usertype = match($this) {
            self::Normal => 'Invoerder',
            self::Editor => 'Redacteur',
            self::EditorInChief => 'Eindredacteur',
            self::Developer => 'Ontwikkelaar',
            self::Administrators => 'Administrator',
        };

        return trans($usertype);
    }

    public function getIcon(): ?string
    {
        return match($this) {
            self::Developer => 'heroicon-o-code-bracket',
            self::Administrators => 'heroicon-o-key',
            self::Editor, self::EditorInChief => 'heroicon-o-penci',
            self::Normal => 'heroicon-o-user-users',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Developer, self::Administrators => 'danger',
            self::Editor, self::EditorInChief => 'gray',
            self::Normal => 'success',
        };
    }
}
