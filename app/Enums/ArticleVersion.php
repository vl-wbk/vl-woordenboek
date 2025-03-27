<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum ArticleVersion: string implements HasLabel, HasDescription, HasColor
{
    use Comparable;

    case Claus = 'v1';
    case Spit = 'v2';
    case Lanoye = 'v3';
    case Vanhauwaert = 'v4';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getColor(): string
    {
        return 'info';
    }

    public function getDescription(): ?string
    {
        return match($this) {
            self::Claus => 'Dit artikel werd nog niet redactioneel bewerkt en daarom kan de kwaliteit ontoereikend zijn.',
            default => null,
        };
    }
}
