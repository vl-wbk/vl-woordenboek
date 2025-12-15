<?php

namespace App\Filament\Clusters\Articles\Resources\ModerationRules\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ModerationRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(heading: fn (string $operation): string => self::getSectionHeading($operation))
                    ->icon(Heroicon::OutlinedDocumentCheck)
                    ->iconColor('primary')
                    ->description(description: fn (string $operation): string => self::getSectionDescription($operation))
                    ->columns(12)
                    ->columnSpanFull()
                    ->compact()
                    ->components(self::getFormComponents())
            ]);
    }

    private static function getSectionHeading(string $operation): string
    {
        return ($operation === 'edit')
            ? __('Taaladvies bewerken')
            : __('Nieuw taaladvies toevoegen');
    }

    private static function getSectionDescription(string $operation): string
    {
        return ($operation === 'edit')
            ? __('Via het onderstaande formulier kunt u het bestaande taaladvies bewerken')
            : __('Via het onderstaande formulier kunt u een nieuw taaladvies toevoegen');
    }

    private static function getFormComponents(): array
    {
        return [
            TextInput::make('category')
                ->label('Categorie')
                ->placeholder('Racisme')
                ->columnSpan(3)
                ->required(),
            TextInput::make('pattern')
                ->label('Patroon/RegEx')
                ->columnSpan(9)
                ->placeholder('bv. neger')
                ->unique(ignoreRecord: true)
                ->required(),
            Textarea::make('explanation')
                ->label('Uitleg/Advies')
                ->columnSpanFull()
                ->rows(3)
                ->placeholder('Deze term geldt als kwetsend of racistisch. Enkel gebruiken in historische context.')
                ->required()
                ->columnSpanFull(),
            Textarea::make('neutral_alternative')
                ->label('Neutrale alternatieven')
                ->placeholder('Zwarte persoon, enz...')
                ->rows(3)
                ->columnSpanFull(),
            TagsInput::make('allowed_contexts')
                ->label('Toegelaten contexten')
                ->columnSpanFull()
                ->helperText('Druk op enter om aan een nieuwe context te beginnen'),
            Toggle::make('is_regex')
                ->label('Dit patroon is een reguliere expressie')
                ->columnSpanFull()
                ->required(),
        ];
    }
}
