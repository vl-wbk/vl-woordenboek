<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

/**
 * Represents the different volunteer positions within the application.
 *
 * This enum defines a fixed set of roles that volunteers can hold, such as
 * Editor, Chief Editor, and Developer. Each case provides a human-readable label and a detailed description, which are useful for display in user interfaces, documentation, or internal system logic.
 * It implements the `HasLabel` and `HasDescription` interfaces from Filament, making it directly compatible with Filament's form fields, tables, and other UI components that require a displayable name and an explanatory text.
 *
 * @package App\Enums
 */
enum VolunteerPositions: int implements HasLabel, HasDescription
{
    /**
     * Represents a volunteer position responsible for editing and updating dictionary data.
     * This role typically involves ensuring the accuracy and consistency of definitions, examples, and linguistic information.
     */
    case Editor = 1;

    /**
     * Represents a volunteer position responsible for overseeing and ensuring the quality of editorial work.
     * This role typically involves maintaining consistency in style, tone, and content, and holds final responsibility for publication-ready material.
     */
    case ChiefEditor = 2;

    /**
     * Represents a volunteer position responsible for the technical development and maintenance of the application.
     * This role typically involves working on functionality, performance, user interface, bug fixing, and implementing new features.
     */
    case Developer = 3;

    /**
     * Retrieves the human-readable label for the current volunteer position.
     * This method provides a concise, display-friendly name for each enum case, suitable for use in dropdowns, labels, or table columns.
     *
     * @return string The localized label of the volunteer position.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Editor => 'Redacteur',
            self::ChiefEditor => 'Eindredacteur',
            self::Developer => 'Ontwikkelaar',
        };
    }

    /**
     * Retrieves a detailed description for the current volunteer position.
     * This method offers an extended explanation of the responsibilities and scope of each volunteer role, useful for tooltips, help texts, or detailed role descriptions.
     *
     * @return string The localized description of the volunteer position.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::Editor => 'Verantwoordelijk voor het invoeren, bewerken en actualiseren van woordenboekgegevens. Controleert definities, voorbeelden en taalkundige informatie op juistheid en consistentie.',
            self::ChiefEditor => 'Toetst en bewaakt de kwaliteit van het werk van redacteuren. Zorgt voor eenheid in stijl, toon en inhoud. Heeft de eindverantwoordelijkheid voor publicatieklare inhoud.',
            self::Developer => 'Beheert en ontwikkelt de technische kant van de applicatie. Werkt aan functionaliteit, prestaties en gebruikersinterface. Lost bugs op en implementeert nieuwe features.',
        };
    }
}
