<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum languageStatus
 *
 * Deze enumeratie voorziet de suggesties en de woord (artikelen) van statussen (in welke mate het woord als 'correct' gebruik hanteren).
 * Hier is verkoezen om een enumeratie toe te passen voor dit. Aangezien het maar een select aantal opties zijn.
 * De statussen worden tevens ook gemapt naar nummers om de opslag in de databank te optimaliseren.
 * In deze enumeratie zullen we zo ook ineens doormiddel van een functie mappen naar het juiste label.
 *
 * @package App\Enums
 */
enum LanguageStatus: int implements HasLabel, HasDescription
{
    /**
     * Respresents a word/suggestion accepted as Standard Dutch.enu
     */
    case StandaardNederlands = 1;

    /**
     * Represents a word/suggestion accepted as standard Belgian Dutch.
     */
    case StandaardBelgischNederlands = 2;

    /**
     * Represents a word/suggestion that is a candidate for standard Belgian Dutch but has not yet been officially accepted.
     */
    case KandidaatBelgischNederlands = 3;

    /**
     * Represents a word/suggestion whose language status is unknown or not yet determined.
     */
    case Onbekend = 4;

    /**
     * Represents a word/suggestion that is a candidate for standard Belgian Dutch but has not yet been officially accepted.
     */
    case GeenStandaardTaal = 5;

    /**
     * Retrieves the displayable label for the enum case.
     *
     * This method uses a match expression to return a translated string corresponding to the current enum case,
     * which is useful for ui display.
     *
     * @return string The translated display label.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::StandaardNederlands => __('enums/language-status.standaard-nederlands'),
            self::StandaardBelgischNederlands => __('enums/language-status.standaard-belgisch-nederlands'),
            self::KandidaatBelgischNederlands => __('enums/language-status.kandidaat-belgisch-nederlands'),
            self::Onbekend => __('enums/language-status.onbekend'),
            self::GeenStandaardTaal => __('enums/language-status.geen-standaard-taal'),
        };
    }

    /**
     * Retrieves a more detailed description for the enum case.
     *
     * This method uses a match expression to return a translated descriptive string corresponding to the current enum case,
     * often used as tooltips or help text.
     *
     * @return string The translated detailed description.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::StandaardNederlands => __('enums/language-status.descriptions.standaard-nederlands'),
            self::StandaardBelgischNederlands => __('enums/language-status.descriptions.standaard-belgisch-nederlands'),
            self::KandidaatBelgischNederlands => __('enums/language-status.descriptions.kandidaat-belgisch-nederlands'),
            self::Onbekend => __('enums/language-status.descriptions.onbekend'),
            self::GeenStandaardTaal => __('enums/language-status.descriptions.geen-standaard-taal'),
        };
    }
}
