<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use App\Attributes\Todo;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

#[Todo(message: 'Write docblocks for this enumeration', priority: 'low')]
enum InsightCategory: int implements HasDescription, HasLabel
{
    case Fact = 1;
    case Region = 2;
    case Usage = 3;
    case Nuance = 4;
    case Other = 5;
    case Uncategorized = 6;

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

    public function getFullDisplayLabel(): string
    {
        return "{$this->value}. {$this->getLabel()} - {$this->getDescription()}";
    }

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
