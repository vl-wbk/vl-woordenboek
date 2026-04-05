<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ExampleSentences\Schema;

use Filament\Schemas\Schema;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

final readonly class ExampleSentenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(components: [
            Textarea::make('example')
                ->required()
                ->rows(5)
                ->label('Voorbeeldzin')
                ->autocomplete(false)
                ->columnSpanFull()
        ]);
    }
}
