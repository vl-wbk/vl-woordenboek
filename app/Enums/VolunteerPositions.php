<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum VolunteerPositions: int implements HasLabel, HasDescription
{
    case Editor = 1;
    case ChiefEditor = 2;
    case Developer = 3;

    public function getLabel(): string
    {
        return match($this) {
            self::Editor => 'Redacteur',
            self::ChiefEditor => 'Eindredacteur',
            self::Developer => 'Ontwikkelaar',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::Editor => 'Verantwoordelijk voor het invoeren, bewerken en actualiseren van woordenboekgegevens. Controleert definities, voorbeelden en taalkundige informatie op juistheid en consistentie.',
            self::ChiefEditor => 'Toetst en bewaakt de kwaliteit van het werk van redacteuren. Zorgt voor eenheid in stijl, toon en inhoud. Heeft de eindverantwoordelijkheid voor publicatieklare inhoud.',
            self::Developer => 'Beheert en ontwikkelt de technische kant van de applicatie. Werkt aan functionaliteit, prestaties en gebruikersinterface. Lost bugs op en implementeert nieuwe features.',
        };
    }
}
