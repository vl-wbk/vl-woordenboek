<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Widgets;

use App\Enums\ArticleStates;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use App\Models\User;
use Deldius\UserField\UserColumn;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use LogicException;

class SuggestionQueueTable extends TableWidget
{
    public const unprocessed = 'unprocessed';
    public const draft = 'draft';
    public const rejected = 'rejected';

    public string $activeTab = self::unprocessed;

    protected int|string|array $columnSpan = 'full';

    public function updatedActiveTab(): void
    {
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->currentTab()['query']($this->baseQuery()))
            ->headerActions($this->getHeaderActions())
            ->heading($this->currentTab()['heading'])
            ->description($this->currentTab()['description'])
            ->emptyStateIcon($this->currentTab()['icon'])
            ->emptyStateHeading($this->currentTab()['emptyStateHeading'])
            ->emptyStateDescription($this->currentTab()['emptyStateDescription'])
            ->recordActions(actions: $this->registerToolbarActions())
            ->filters(filters: $this->registerFilters())
            ->recordUrl(fn (Article $article): string => ArticleResource::getUrl('view', ['record' => $article]))
            ->columns($this->tableLayoutColumns())
            ->paginated([7, 14, 21, 28]);
    }

    public function render(): \Illuminate\View\View
    {
        return view('filament.widgets.suggestion-queue-table', [
            'tabs' => collect($this->tabs())
                ->map(fn (array $tab) => [
                    'label' => $tab['menuLabel'],
                    'badge' => $tab['query']($this->baseQuery())->count(),
                    'icon' => $tab['icon'],
                ])
                ->all(),
        ]);
    }

    /**
     * @return SelectFilter[]
     */
    private function registerFilters(): array
    {
        return [
            SelectFilter::make('state')
                ->label('Status')
                ->native(false)
                ->options([
                    ArticleStates::New ->value => ArticleStates::New ->getLabel(),
                    ArticleStates::ExternalData->value => ArticleStates::ExternalData->getLabel(),
                    ArticleStates::Draft->value => ArticleStates::Draft->getLabel(),
                    ArticleStates::RejectedPublication->value => ArticleStates::RejectedPublication->getLabel(),
                ])
        ];
    }

    /**
     * @return array<EditAction|ViewAction>
     */
    private function registerToolbarActions(): array
    {
        return [
            ViewAction::make()
                ->label('Bekijken')
                ->url(fn (Article $article): string => ArticleResource::getUrl('view', ['record' => $article])),

            EditAction::make()
                ->url(fn (Article $article): string => ArticleResource::getUrl('edit', ['record' => $article])),
        ];
    }

    private function tableLayoutColumns(): array
    {
        return [
            TextColumn::make('created_at')
                ->label('Ingezonden op')
                ->weight(FontWeight::Bold)
                ->color('primary')
                ->sortable()
                ->sinceTooltip(),
            TextColumn::make('state')
                ->label('Status')
                ->sortable()
                ->toggleable()
                ->toggledHiddenByDefault()
                ->badge(),
            UserColumn::make('author_id')
                ->description(fn (Article $article): string => "{$article->author->firstname} {$article->author->lastname}")
                ->emptyStateHeading(config('app.name', 'Laravel')) // Custom empty state heading
                ->emptyStateDescription(fn (Article $article): string => $article->contributor_name ?? 'Anonieme gebruiker')
                ->label('Ingezonden door'),
            TextColumn::make('word')
                ->label('Lemma')
                ->searchable(),
            TextColumn::make('description')
                ->limit(100),
        ];
    }

    /**
     * Single source of truth for every tab: menu label, heading, icon,
     * and the query filter applied on top of the base editor query.
     *
     * @return array<string, array{menuLabel: string, heading: string, icon: Heroicon, query: \Closure(Builder): Builder}>
     */
    private function tabs(): array
    {
        return [
            self::unprocessed => [
                'menuLabel' => 'Onbehandelde suggesties',
                'emptyStateHeading' => 'Geen onbehandelde suggesties gevonden',
                'emptyStateDescription' => 'het lijkt erop dat we weer helemaal mee zijn met de artikelen van het Vlaams Woordenboek! Kom later nog eens terug',
                'heading' => __('Suggestie wachtrij'),
                'description' => 'Alle door gebruikers ingediende suggesties die wachten op behandeld te worden.',
                'icon' => Heroicon::OutlinedQueueList,
                'query' => fn (Builder $query): Builder => $query
                    ->whereIn('state', [ArticleStates::New, ArticleStates::ExternalData]),
            ],

            self::draft => [
                'menuLabel' => 'Mijn Kladartikelen',
                'emptyStateHeading' => 'Geen kladartikelen gevonden',
                'emptyStateDescription' => 'Het lijkt erop dat je momenteel geen artikelen hebt die je actief aan het bewerken bent',
                'heading' => __('Mijn kladartikelen'),
                'description' => 'Een overzicht van alle artikelen die je hebt gekozen om te bewerken',
                'icon' => Heroicon::OutlinedPencilSquare,
                'query' => fn (Builder $query): Builder => $query
                    ->forEditor($this->user())
                    ->where('state', ArticleStates::Draft),
            ],

            self::rejected => [
                'menuLabel' => 'Mijn afgewezen publicaties',
                'heading' => __('Mijn afgewezen publicaties'),
                'emptyStateHeading' => 'Geen afgewezen publicaties gevonden',
                'emptyStateDescription' => 'Het lijkt er op dat je momenteel geen artikelen hebt staan die verdere verfijning nodig hebben.',
                'description' => 'Overzicht van alle artikelen die net iets meer verfijning nodig hebben alvorens ze gepubliceerd worden',
                'icon' => Heroicon::OutlinedXCircle,
                'query' => fn (Builder $query): Builder => $query
                    ->forEditor($this->user())
                    ->where('state', ArticleStates::RejectedPublication),
            ],
        ];
    }

    private function currentTab(): array
    {
        return $this->tabs()[$this->activeTab]
            ?? throw new LogicException("Unknown tab [{$this->activeTab}].");
    }

    private function getHeaderActions(): ?array
    {
        if (in_array($this->activeTab, [self::unprocessed, self::draft])) {
            return [
                Action::make('create-article-action')
                    ->label('Artikel toevoegen')
                    ->color('gray')
                    ->icon(Heroicon::OutlinedDocumentPlus)
                    ->url(ArticleResource::getUrl('create'))
            ];
        }

        return [];
    }

    private function baseQuery(): Builder
    {
        return ArticleResource::getEloquentQuery();
    }

    private function user(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }
}
