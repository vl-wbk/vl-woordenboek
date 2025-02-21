<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SuggestionStatus: string implements HasIcon, HasColor, HasLabel
{
    use Comparable;

    case New = 'onbehandeld';
    case InProgress = 'In behandeling';
    case Rejected = 'Afgewezen';
    case Accepted = 'Geaccepteerd';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match($this) {
            self::New => 'info',
            self::InProgress => 'warning',
            self::Rejected => 'danger',
            self::Accepted => 'success',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::New => 'heroicon-o-document-text',
            self::InProgress => 'heroicon-o-clipboard-document-list',
            self::Rejected => 'heroicon-o-document-minus',
            self::Accepted => 'heroicon-o-document-check',
        };
    }

    public function getStatisticDescription(): string
    {
        return match($this) {
            self::New => 'Onbehandelde suggesties',
            self::InProgress => 'Suggesties in behandeling',
            self::Accepted => 'Geaccepteerde suggesties',
            self::Rejected => 'Afgewezen suggesties'
        };
    }
}
