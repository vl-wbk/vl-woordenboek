<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

/**
 * InsightCategory 
 * 
 * This enumeration defines the clasification system for insights and feedback regarding article content. 
 * It provides a structured way to categorize the nature of a correctioon or addition, facilitating better editorial filtering. 
 * 
 * By implementing Filament's 'HalsLabel' and 'hasDescription', this enum integrates natively with admin panel components 
 * like Select inputs, Radiuo button groups, and Information lists to provide rich, descriptive options for content editors. 
 * 
 * Future maintainers: When adding a new category, ensure u update both 'getLabel()' for the concise name and 'getDescription()'
 * for the instructional text that explains when to use the category. 
 * 
 * @package App\Enums\Articles
 */
enum InsightCategory: int implements HasDescription, HasLabel
{
    case Fact = 1;
    case Region = 2;
    case Usage = 3;
    case Nuance = 4;
    case Other = 5;
    case Uncategorized = 6;

    /**
     * Retrieve the concise display label. 
     * Used primarly in tables, badges, and headers where space is limited. 
     *
     * @return string The localized or standard name of the category.
     */
    public function getLabel(): string
    {
       return match ($this) {
           self::Fact => 'Fact',
           self::Region => 'Regio',
           self::Usage => 'Gebruik',
           self::Nuance => 'Nuance',
           self::Other => 'Overig',
           self::Uncategorized => 'Ongecategoriseerd',
       };
    }

    /**
     * Generate a comprehensive summary string. 
     * 
     * Combines the internal value, label, and full description into a single string, 
     * typically used for detailed logging or select options where the user needs
     * context for each choice. 
     *
     * @return string Formatted as "ID. Label - Description"
     */
    public function getFullDisplayLabel(): string
    {
        return "{$this->value}. {$this->getLabel()} - {$this->getDescription()}";
    }

    /**
     * Retrieve the instructional description.
     * 
     * Provides clear guidance on the intended use case for each category, 
     * helping editors choose the most accurate classification for a piece of feedback.
     * 
     * @return string Detailed explanation of the category's scope.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::Fact => 'Feitelijke correctie/aanvulling, de definitie is onjuist of onvolledig',
            self::Region => "Regionaal of dialect verschil. Er ontbreekt context over het gebruik in andere regio's",
            self::Usage => 'Onnatuurlijke voorbeelden/Verouderde voorbeelden. De voorbeeldzin klinkt raar of is niet meer van deze tijd.',
            self::Nuance => 'Stijl/Formatie/Jargon. Gebruiker mist info over het formele of informele gebruik.',
            self::Other => 'Vragen/log/niet relevant. Feedback die niet direct een inhoudelijke wijziging vereist.',
            self::Uncategorized => 'Deze entry heeft nog geen categorie toegewezen gekregen',
        };
    }
}
