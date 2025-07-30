<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

/**
 * Represents the various etymological types or origins of words.
 *
 * This enum defines a fixed set of common ways in which words can originate or enter a language, such as borrowing, calque, neologism, and more.
 * Each case is equipped with metadata via attributes (`#[Label]` and `#[Description]`) to provide a human-readable label and a detailed description.
 *
 * The enum implements the `HasLabel` and `HasDescription` interfaces from Filament, which makes it easy to use these etymological types in UI components
 * like forms, tables, or select boxes, where both a concise display name and an extended explanation are required.
 *
 * The `Metadata` trait from `ArchTech\Enums` is used to provide the functionality for retrieving the attributes as methods (e.g., `self::label()`
 * and `self::description()`), which are then utilized in the implementation of `getLabel()` and `getDescription()`.
 *
 * @method static string description()  Retrieves the description for the current enum case.
 * @method static string label()        Retrieves the label for the current enum case.
 *
 * @package App\Enums\Articles
 */
enum EtymologyTypes: int implements HasLabel, HasDescription
{
    case Borrowing = 1;
    case Calque = 2;
    case Neologism = 3;
    case Compound = 4;
    case Derivation = 5;
    case Onomatopoeia = 6;
    case Abbreviation = 7;
    case Blending = 8;
    case Backformation = 9;
    case FolkEtymology = 10;
    case Inherit = 11;
    case Unknown = 12;

    /**
     * Retrieves the human-readable label for the current etymology type.
     * This method leverages the `#[Label]` attribute defined on each enum case to provide a display-friendly name, suitable for UI elements.
     *
     * @return string The label of the etymology type.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Borrowing => 'ontlening',
            self::Calque => 'leenvertaling',
            self::Neologism => 'neologisme',
            self::Compound => 'samenstelling',
            self::Derivation => 'afleiding',
            self::Onomatopoeia => 'klanknabootsing',
            self::Abbreviation => 'afkorting',
            self::Blending => 'samentrekking',
            self::Backformation => 'terugvorming',
            self::FolkEtymology => 'volksetymologie',
            self::Inherit => 'erfwoord',
            self::Unknown => 'onbekend',
        };
    }

    /**
     * Retrieves a detailed description for the current etymology type.
     * This method uses the `#[Description]` attribute associated with each enum case to offer an extended explanation of what the etymology type signifies.
     *
     * @return string The description of the etymology type.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::Borrowing => 'Het woord is rechtstreeks overgenomen uit een andere taal.',
            self::Calque => 'Letterlijke vertaling van een buitenlands woord.',
            self::Neologism => 'Nieuw woord gevormd in de eigen taal.',
            self::Compound => 'Woord gevormd door combinatie van twee of meer bestaande woorden (bv. regenjas).',
            self::Derivation => 'Woord gevormd door het toevoegen van een voor- of achtervoegsel (bv. werkloos van werk).',
            self::Onomatopoeia => 'Onomatopee: woord gevormd naar klank (bv. tsjilp, boem).',
            self::Abbreviation => 'Verkorte vorm van een langer woord of uitdrukking (bv. tv van televisie).',
            self::Blending => 'Woord gevormd door het combineren van delen van woorden (bv. brunch uit breakfast en lunch).',
            self::Backformation => 'Nieuw woord ontstaat door het weglaten van een (vermeend) achtervoegsel (bv. redact uit redacteur — hypothetisch voorbeeld).',
            self::FolkEtymology => 'Vervorming op basis van een verkeerde interpretatie (bv. sparrowgrass voor asparagus in het Engels).',
            self::Inherit => 'Het woord is geërfd uit een oudere vorm van dezelfde taal.',
            self::Unknown => 'De oorsprong is niet met zekerheid vast te stellen.',
        };
    }
}
