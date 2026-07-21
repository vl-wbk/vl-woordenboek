<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\CorrectionProposals\Schemas;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Support\Enums\FontWeight;

final readonly class CorrectionProposalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::correctionInformationTabs(),

                Flex::make([
                    self::liveArticleSection(),
                    self::correctionInformationForm(),
                ])
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'items-stretch']),
                ]);
    }

    private static function correctionInformationForm(): Section 
    {
        return Section::make('Correctie voorstel')
            ->description('Overzicht van de correctiee die aangeleverd is door de gebruiker')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->extraAttributes(['class' => 'h-full'])
            ->iconColor('primary')
            ->compact()
            ->columnSpan(6)
            ->columns(12)
            ->schema(components: [
                TextEntry::make('article.word')
                    ->columnSpan(4)
                    ->weight(FontWeight::ExtraBold)
                    ->color('primary')
                    ->label('Trefwoord'),

                TextEntry::make('keywords')
                    ->label('Kernwoorden')
                    ->placeholder('- geen opgegeven')
                    ->columnSpan(8),

                MarkdownEditor::make('description')
                    ->label('Voorgestelde correctie (artikel beschrijving)')
                    ->required()
                    ->minHeight('250px')
                    ->columnSpanFull()
                    ->helperText("Je kan de correctie verbeteren, gelieve dit enkel te doen bij typo's. Klopt de correctie niet wijs deze dan af via de onderstaande knop"),
            ]);
    }

    private static function liveArticleSection(): Section 
    {
        return Section::make('Gegevens uit het publiek artikel')
            ->icon(Heroicon::OutlinedBookOpen)
            ->iconColor('primary')
            ->extraAttributes(['class' => 'h-full'])
            ->description('Enkel de gegevens die betrekking hebben tot de correctie worden hier getoond')
            ->compact()
            ->columns(12)
            ->columnSpan(6)
            ->schema([
                TextEntry::make('article.word')
                    ->columnSpan(4)
                    ->weight(FontWeight::ExtraBold)
                    ->color('primary')
                    ->label('Trefwoord'),

                TextEntry::make('keywords')
                    ->label('Kernwoorden')
                    ->color('gray')
                    ->placeholder('- geen opgegeven')
                    ->columnSpan(8),

                TextEntry::make('article.description')
                    ->label('Beschrijving van het trefwoord')
                    ->columnSpan(12)
                    ->markdown()
            ]);
    }

    public static function correctionInformationTabs(): Section
    {
        return Section::make('Metadata en status')
            ->icon(Heroicon::OutlinedInformationCircle)
            ->description('Overzicht van randgegevens zoals de auteur van de correctie, status en beweegredenen')
            ->iconColor('primary')
            ->compact()
            ->columnSpanFull()
            ->collapsed()
            ->columns(12)
            ->schema(components: [
                TextEntry::make('state')
                    ->label('Status')
                    ->badge()
                    ->columnSpan(3),

                TextEntry::make('author.name')
                    ->label('Ingezonden door')
                    ->weight(FontWeight::ExtraBold)
                    ->columnSpan(3)
                    ->color('primary'),

                TextEntry::make('created_at')
                    ->label('Ingezonden op')
                    ->date(format: 'd/m/Y - H:i:s')
                    ->columnSpan(3),

                TextEntry::make('reason')
                    ->color('gray')
                    ->columnSpanFull()
                    ->label('Beweegredenen')
            ]);
    }
}
