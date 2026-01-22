<?php 

declare(strict_types=1); 

namespace App\Enums\Articles;

use Filament\Support\Contracts\HasLabel;

enum ArticleDeletionReasons: string implements HasLabel
{
    case SpeechVariation = 'Uitspraakvariant';
    case Double = 'Dubbel';
    case ReferenceArticle = 'Verwijsartikel'; 
    case ImproperArticle = 'Oneigenlijk artikel';
    case TooRegional = 'Te regionaal';
    case WritingVariant = 'Schrijfvariant'; 
    case Junk = 'Rommel';
    case Other = 'Andere';

    public function getLabel(): string 
    {
        return $this->value;
    }
}