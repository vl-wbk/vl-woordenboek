<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ExampleSentences\Tables;

use A909M\FilamentStateFusion\Actions\StateFusionAction;
use A909M\FilamentStateFusion\Actions\StateFusionBulkAction;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use App\Models\UserExample;
use App\Policies\UserExamplePolicy;
use App\States\ExampleSentence\Rejected;
use App\States\ExampleSentence\Approved;
use App\States\ExampleSentence\Pending;
use App\States\ExampleSentence\Unpublished;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final readonly class ExampleSentencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['exampleable:id,word', 'author:id,name'])->whereMorphRelation('exampleable', Article::class, 'id', '!=', null))
            ->heading(heading: 'Overzicht van Voorbeeldzinnen')
            ->description(description: 'Een overzicht van alle Voorbeeldzinnen die zijn aangedragen door gebruikers van het Vlaams Woordenboek. In de onderstaande tabel vind je een overzicht van alle voorbeelden die nog beoordeeld moeten worden.')
            ->headerActions(actions: self::registerTableHeaderActions())
            ->emptyStateIcon(icon: Heroicon::OutlinedQueueList)
            ->emptyStateHeading(heading: 'Geen voorbeeldzinnen gevonden')
            ->emptyStateDescription(description: 'Er zijn momenteel geen voorbeeldzinnen gevonden vanuit de community die nog geoordeeld moeten worden')
            ->columns(components: self::registerTableColumns())
            ->recordActions(actions: self::registerRecordActions())
            ->toolbarActions(actions: self::registerToolbarActions());
    }

    /**
     * @return BulkActionGroup[]
     */
    private static function registerToolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                StateFusionBulkAction::make('approve')
                    ->authorize(UserExamplePolicy::changeStateAny)
                    ->label('Goedkeuren')
                    ->icon(Heroicon::OutlinedCheckBadge)
                    ->modalCloseButton(false)
                    ->deselectRecordsAfterCompletion()
                    ->outlined()
                    ->modalHeading('Voorbeelzinnen goedkeuren')
                    ->modalDescription('U staat op het punt om community voorbeeldzinnen goed te keuren in het Vlaams Woordenboek. Weet je zeker dat je wilt uitvoeren?')
                    ->transition(Pending::class, Approved::class),

                ActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Geselecteerde voorbeeldzinnen verwijderen'),
                ])->dropdown(false)
            ])
        ];
    }

    /**
     * @return TextColumn[]
     */
    private static function registerTableColumns(): array
    {
        return [
            TextColumn::make('exampleable.word')
                ->label('artikel')
                ->icon(Heroicon::OutlinedDocumentText)
                ->iconColor('primary')
                ->weight(FontWeight::Bold)
                ->color('primary')
                ->url(fn (UserExample $userExample): string => ArticleResource::getUrl('view', ['record' => $userExample->exampleable]))
                ->searchable()
                ->sortable(),

            TextColumn::make('author.name')
                ->label('ingezonden door')
                ->icon(Heroicon::OutlinedUserCircle)
                ->searchable()
                ->sortable(),

            TextColumn::make('example')
                ->label('Voorbeeldzin')
                ->limit(75),

            TextColumn::make('created_at')
                ->label('ingezonden op')
                ->date()
                ->sortable(),
        ];
    }

    /**
     * @return StateFusionAction[]
     */
    private static function registerRecordActions(): array
    {
        return [
            StateFusionAction::make('approve')
                ->authorize(UserExamplePolicy::changeState)
                ->label('Publiceren')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->transitionTo(Approved::class),

            EditAction::make()
                ->modalHeading('Community voorbeeldzin bewerken')
                ->modalIcon(Heroicon::OutlinedPencilSquare)
                ->modalDescription('Staat er een typo in de voorbeeldzin? Geen probleem u kunt deze oplossen door het onderstaande formulier.')
                ->modalCloseButton(false),

            StateFusionAction::make('offline')
                ->authorize(UserExamplePolicy::changeState)
                ->label('Offline halen')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->transitionTo(Unpublished::class),

            StateFusionAction::make('reject')
                ->authorize(UserExamplePolicy::changeState)
                ->label('Afwijzen')
                ->icon(Heroicon::XMark)
                ->transitionTo(Rejected::class),
        ];
    }

    /**
     * @return Action[]
     */
    private static function registerTableHeaderActions(): array
    {
        return [
            Action::make('help')
                ->label('Help')
                ->icon(Heroicon::OutlinedLifebuoy)
        ];
    }
}
