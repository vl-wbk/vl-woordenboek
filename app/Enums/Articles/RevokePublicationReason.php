<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum RevokePublicationReason: string implements HasLabel, HasDescription
{
    case TooRegional = 'Te regionaal/dialectisch';
    case Maintenance = 'Onderhoud';
    case PronunciationVariant = 'Uitspraakvariant';
    case Other = 'Andere redenen';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::TooRegional => 'Dit artikel is te regionaal of dialectisch waardoor de publicatie is ingetrokken in het Vlaams Woordenboek',
            self::PronunciationVariant => ' Deze uitspraakvariant mag verwijderd worden',
            self::Maintenance => 'Dit artikel wordt voor (groot) onderhoud tijdelijk offline gehaald.',
            default => null,
        };
    }
}
