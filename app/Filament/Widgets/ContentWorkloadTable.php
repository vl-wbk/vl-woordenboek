<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\ArticleStates;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Deldius\UserField\UserColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

final class ContentWorkloadTable extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => ArticleResource::getEloquentQuery()->whereIn('state', [ArticleStates::New , ArticleStates::ExternalData]))
            ->heading('Nieuwe suggesties')
            ->emptyStateIcon(Heroicon::OutlinedInbox)
            ->emptyStateHeading('Geen Suggesties gevonden')
            ->emptyStateDescription('Momenteel zijn alle suggesties in behandeling of zijn er geen suggesties gevonden die matchen met je zoek term')
            ->description('Een kort overzicht van nieuwe suggesties die zijn binnengekomen en opgenomen kunnen worden door een (eind)redacteur. Suggesties die al geclaimd zijn kunnen bekeken worden in het woordenboek overzicht')
            ->headerActions(actions: $this->getHeaderActions())
            ->columns(components: $this->tableLayoutColumns())
            ->recordActions(actions: $this->registerToolbarActions())
            ->paginated([7, 14, 21, 28]);
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

    /**
     * @return array<TextColumn|UserColumn>
     */
    private function tableLayoutColumns(): array
    {
        return [
            TextColumn::make('created_at')
                ->label('Ingezonden op')
                ->weight(FontWeight::Bold)
                ->color('primary')
                ->sortable()
                ->sinceTooltip(),
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
     * @return Action[]
     */
    private function getHeaderActions(): array
    {
        return [
            Action::make('Artikel toevoegen')
                ->icon(Heroicon::OutlinedDocumentPlus)
                ->url(ArticleResource::getUrl('create'))
                ->color('gray'),

            Action::make('artikelen overzicht')
                ->icon(Heroicon::BookOpen)
                ->url(ArticleResource::getUrl('index'))
        ];
    }
}
