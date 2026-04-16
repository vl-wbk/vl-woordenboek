<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\RelationManagers;

use A909M\FilamentStateFusion\Actions\StateFusionAction;
use A909M\FilamentStateFusion\Actions\StateFusionBulkAction;
use A909M\FilamentStateFusion\Tables\Columns\StateFusionSelectColumn;
use A909M\FilamentStateFusion\Tables\Filters\StateFusionSelectFilter;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Actions\MigrateExamplesAction;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Schema\ExampleSentenceForm;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Actions\CreateAction;
use App\Filament\Resources\Articles\Pages\ViewWord;
use App\Policies\UserExamplePolicy;
use App\States\ExampleSentence\Approved;
use App\States\ExampleSentence\Pending;
use App\States\ExampleSentence\Rejected;
use App\States\ExampleSentence\Unpublished;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class CommunityExamplesRelationManager extends RelationManager
{
    protected static string $relationship = 'userExamples';

    protected static ?string $title = 'Voorbeeldzinnen';

    protected static string|BackedEnum|null $icon = 'heroicon-o-chat-bubble-left-right';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        if ($ownerRecord->userExamples->count() > 0) {
            return (string) $ownerRecord->userExamples->count();
        }

        return null;
    }

    public function form(Schema $schema): Schema
    {
        return ExampleSentenceForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(heading: 'Community voorbeeldzinnen')
            ->description(description: 'Voorbeeldzinnen die zijn bijgedragen door de gebruikers van het Vlaams Woordenboek')
            ->headerActions(actions: $this->registerHeaderActions())
            ->emptyStateIcon(self::$icon)
            ->emptyStateHeading(heading: 'Geen voorbeeldzinnen gevonden')
            ->emptyStateDescription(description: 'Momenteel zijn er geen voorbeeldzinnen gevonden die door de community zijn bijgedragen.')
            ->columns(components: $this->registerTableComponents())
            ->toolbarActions(actions: $this->registerToolbarActions())
            ->filters(filters: $this->registerTableFilters())
            ->recordActions(actions: $this->registerRecordActions());
    }

    private function registerToolbarActions(): array
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

    private function registerTableFilters(): array
    {
        return [
            StateFusionSelectFilter::make('status')
                ->native(false),
        ];
    }

    private function registerRecordActions(): array
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

            StateFusionAction::make('reject')
                ->authorize(UserExamplePolicy::changeState)
                ->label('Afwijzen')
                ->icon(Heroicon::XMark)
                ->transitionTo(Rejected::class),

            StateFusionAction::make('unpublish')
                ->label('Offline halen')
                ->authorize(UserExamplePolicy::changeState)
                ->icon(Heroicon::OutlinedEyeSlash)
                ->transitionTo(Unpublished::class),

        ];
    }

    private function registerTableComponents(): array
    {
        return [
            TextColumn::make('author.name')
                ->label('Ingezonden door')
                ->icon(Heroicon::OutlinedUserCircle)
                ->weight(FontWeight::Bold)
                ->iconColor('primary')
                ->color('primary')
                ->sortable()
                ->searchable(),

            TextColumn::make('status')
                ->sortable()
                ->badge(),

            TextColumn::make('example')
                ->label('Voorbeeldzin')
                ->searchable(),

            TextColumn::make('created_at')
                ->label('Geregistreerd op')
                ->date()
                ->sortable(),
        ];
    }

    private function registerHeaderActions(): array
    {
        return [
            MigrateExamplesAction::make(),

            CreateAction::make()
                ->label('Voorbeelzin toevoegen')
                ->modalHeading('Voorbeeldzin toevoegen in het Vlaams Woordenboek')
                ->modalDescription('Met voorbeeldzinnen worden het gebruik van het woord beter zichtbaar.')
                ->modalIcon(Heroicon::OutlinedPlusCircle)
                ->modalIconColor('primary'),
        ];
    }
}
