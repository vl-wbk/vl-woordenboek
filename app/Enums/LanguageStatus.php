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
 */
enum LanguageStatus: int implements HasDescription, HasLabel
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
            self::StandaardNederlands => 'Een officieel erkend woord dat voorkomt in Nederlandse woordenboeken en algemeen gebruikt wordt in Nederland en Belgie in formele en informele contexten.',
            self::StandaardBelgischNederlands => 'Een officieel erkend woord dat specifiek is voor het Belgisch Nederlands, voorkomt in Belgische woordenboeken en algemeen aanvaard is in België.',
            self::KandidaatBelgischNederlands => 'Een woord dat frequent gebruikt wordt in België maar (nog) niet officieel erkend is als standaardtaal, mogelijk op weg naar erkenning.',
            self::Onbekend => 'Een woord waarvan de status nog niet bepaald is of waarover onvoldoende informatie beschikbaar is',
            self::GeenStandaardTaal => 'Een woord dat niet tot de standaardtaal behoort, zoals dialect of streektaal, en niet algemeen aanvaard is in formele contexten.',
        };
    }
}
