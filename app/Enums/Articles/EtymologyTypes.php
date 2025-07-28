<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use App\Enums\MetaProperties\Description;
use App\Enums\MetaProperties\Label;
use ArchTech\Enums\Meta\Meta;
use ArchTech\Enums\Metadata;
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
#[Meta(Description::class, Label::class)]
enum EtymologyTypes: int implements HasLabel, HasDescription
{
    use Metadata;

    #[Label('ontlening')]
    #[Description('Het woord is rechtstreeks overgenomen uit een andere taal.')]
    case Borrowing = 1;

    #[Label('leenvertaling')]
    #[Description('Letterlijke vertaling van een buitenlands woord.')]
    case Calque = 2;

    #[Label('neologisme')]
    #[Description('Nieuw woord gevormd in de eigen taal.')]
    case Neologism = 3;

    #[Label('samenstelling')]
    #[Description('Woord gevormd door combinatie van twee of meer bestaande woorden (bv. regenjas).')]
    case Compound = 4;

    #[Label('afleiding')]
    #[Description('Woord gevormd door het toevoegen van een voor- of achtervoegsel (bv. werkloos van werk).')]
    case Derication = 5;

    #[Label('klanknabootsing')]
    #[Description('Onomatopee: woord gevormd naar klank (bv. tsjilp, boem).')]
    case Onomatopoeia = 6;

    #[Label('afkorting')]
    #[Description('Verkorte vorm van een langer woord of uitdrukking (bv. tv van televisie).')]
    case Abbreviation = 7;

    #[Label('samentrekking')]
    #[Description('Woord gevormd door het combineren van delen van woorden (bv. brunch uit breakfast en lunch).')]
    case Blending = 8;

    #[Label('terugvorming')]
    #[Description('Nieuw woord ontstaat door het weglaten van een (vermeend) achtervoegsel (bv. redact uit redacteur — hypothetisch voorbeeld).')]
    case Backformation = 9;

    #[Label('volksetymologie')]
    #[Description('Vervorming op basis van een verkeerde interpretatie (bv. sparrowgrass voor asparagus in het Engels).')]
    case FolkEtymology = 10;

    #[Label('erfwoord')]
    #[Description('Het woord is geerd uit een oudere vorm van dezelfde taal.')]
    case Inherit = 11;

    #[Label('onbekend')]
    #[Description('De oorsprong is niet met zekerheid vast te stellen.')]
    case unknown = 12;

    /**
     * Retrieves the human-readable label for the current etymology type.
     * This method leverages the `#[Label]` attribute defined on each enum case to provide a display-friendly name, suitable for UI elements.
     *
     * @return string The label of the etymology type.
     */
    public function getLabel(): string
    {
        return self::label();
    }

    /**
     * Retrieves a detailed description for the current etymology type.
     * This method uses the `#[Description]` attribute associated with each enum case to offer an extended explanation of what the etymology type signifies.
     *
     * @return string The description of the etymology type.
     */
    public function getDescription(): string
    {
        return self::description();
    }
}
