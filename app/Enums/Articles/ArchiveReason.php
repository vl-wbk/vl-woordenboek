<?php

namespace App\Enums\Articles;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum ArchiveReason: string implements HasLabel, HasDescription
{
    case TooGeneral = 'Te algemeen';
    case TooRegional = 'Te regionaal/dialectisch';
    case TooNiche = 'Jargon, vakterminologie';
    case SubStandardQuality = 'Ondermaats in kwaliteit';

    case Double = 'Dubbele entry';

    case Other = 'Andere redenen';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::TooGeneral => 'te algemeen, niet typisch of overwegend gebruikelijk in Vlaanderen',
            self::TooRegional => 'te regionaal of dialectisch, alleen lokaal bekend',
            self::Double => 'dubbel, staat al in het Vlaams Woordenboek',
            self::TooNiche => 'te specialistisch, te niche voor het Vlaams Woordenboek',
            self::SubStandardQuality => 'te lage kwaliteit: vaag, te weinig info',
            default => null,
        };
    }
}
