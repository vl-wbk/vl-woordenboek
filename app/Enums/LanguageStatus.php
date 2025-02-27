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
    case StandaardNederlands = 1;
    case StandaardBelgischNederlands = 2;
    case KandidaatBelgischNederlands = 3;
    case Onbekend = 4;
    case GeenStandaardTaal = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::StandaardNederlands => 'Standaard nederlands',
            self::StandaardBelgischNederlands => 'Standaard Belgisch-Nederlands',
            self::KandidaatBelgischNederlands => 'Kandidaat Belgisch-Nederlands',
            self::Onbekend => 'Onbekend',
            self::GeenStandaardTaal => 'Geen standaardtaal',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::StandaardNederlands => 'Dit is de status beschrijving voor Standaard Nederlands',
            self::StandaardBelgischNederlands => 'Dit is de status beschrijving voor standaard Belgisch Nederlands',
            self::KandidaatBelgischNederlands => 'Dit is de status beschrijving voor kandidaat Belgisch Nederlands',
            self::Onbekend => 'Er is geen status veld gekend dat matcht bij dit woord',
            self::GeenStandaardTaal => 'Zelf Yoda kan niet uit aan deze standarisatie. Nook! Nook!',
        };
    }
}
