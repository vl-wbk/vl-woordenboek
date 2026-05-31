<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\CorrectionProposals\Tables;

use A909M\FilamentStateFusion\Tables\Filters\StateFusionSelectFilter;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\CorrectionProposal;
use App\States\Articles\Corrections\PendingState;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

final readonly class CorrectionProposalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Correctie voorstellen')
            ->description('Een centraal overzicht van alle door gebruikers ingediende wijzigingen voor het Vlaams Woordenboek.')
            ->emptyStateIcon(icon: Heroicon::PencilSquare)
            ->emptyStateHeading(heading: 'Geen correctie voorstellen gevonden')
            ->emptyStateDescription('Momenteel zijn alle voorstellen behandeld of zijn er geen gevonden matchende met je criteria kom later nog een terug.')
            ->columns(components: self::registerTableColumnComponents())
            ->filters(filters: self::registerTableFilters(), layout: FiltersLayout::Modal)
            ->recordActions(actions: self::registerRecordActions());
    }

    private static function registerRecordActions(): array 
    {
        return [
            EditAction::make()
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->label('Behandelen'),

            ViewAction::make()
                ->color('gray')
                ->icon(Heroicon::OutlinedEye)
                ->label('Bekijken')
                ->modalIcon(Heroicon::OutlinedPencilSquare)
                ->modalIconColor(fn (CorrectionProposal $correctionProposal): string => $correctionProposal->state->getColor())
                ->modalHeading(fn (CorrectionProposal $correctionProposal): string => "Correctie #{$correctionProposal->id} - algemene informatie")
                ->modalDescription('Alle gegevens omtrent de voorgestelde correctie die zijn ingezonden door de gebruiker'),
        ];
    }

    private static function registerTableFilters(): array 
    {
        return [
            StateFusionSelectFilter::make('state')
                ->label('status')
                ->native(false),
        ];
    }

    private static function registerTableColumnComponents(): array 
    {
        return [
            TextColumn::make('author.name')
                ->weight(FontWeight::ExtraBold)
                ->color('primary')
                ->label('ingezonden door')
                ->sortable()
                ->searchable(),

            TextColumn::make('moderator.name')
                ->label('behandeld door')
                ->sortable()
                ->searchable()
                ->toggleable()
                ->toggledHiddenByDefault(),

            TextColumn::make('state')
                ->label('status')
                ->badge(),

            TextColumn::make('article.word')
                ->label('artikel')
                ->url(fn (CorrectionProposal $correctionProposal): string => ArticleResource::getUrl('view', ['record' => $correctionProposal->article]))
                ->searchable(),

            TextColumn::make('moderated_at')
                ->label('behandeld op')
                ->date(format: 'd/m/Y - h:i:s')
                ->placeholder('-')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('ingezonden op')
                ->date(format: 'd/m/Y - h:i:s')
                ->sortable(),
        ];
    }
}
