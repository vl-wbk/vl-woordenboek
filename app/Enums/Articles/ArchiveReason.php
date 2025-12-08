<?php

namespace App\Enums\Articles;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum ArchiveReason: string implements HasLabel, HasDescription
{
    case TooGeneral = 'Te algemeen';
    case TooRegional = 'Te regionaal/dialectisch';
    case SubStandardQuality = 'Kwaliteit ondermaats';

    case Other = 'Andere redenen';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::TooGeneral => 'Dit artikel is te algemeen om actief te blijven in het Vlaams Woordenboek',
            self::TooRegional => 'Dit artikel is te regionaal of dialectisch waardoor het gearchiveerd is in het Vlaams Woordenboek',
            self::SubStandardQuality => 'Dit artikel is te ondermaats in kwaliteit en daardoor gearchiveerd',
            default => null,
        };
    }
}
