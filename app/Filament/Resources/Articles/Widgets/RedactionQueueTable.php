<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Widgets;

use App\Enums\ArticleStates;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Deldius\UserField\UserColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

final class RedactionQueueTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Article::query()->where('state', ArticleStates::Approval))
            ->recordUrl(fn (Article $article): string => ArticleResource::getUrl('view', ['record' => $article]))
            ->heading('Eindredactie wachtrij')
            ->description('Een overzicht van alle artikelen die zijn aangeboden ter publicatie maar nog moeten nagekeken worden door een eindredacteur')
            ->emptyStateIcon(Heroicon::OutlinedInbox)
            ->emptyStateHeading('Geen artikelen gevonden/aangeboden')
            ->emptyStateDescription('Het lijkt erop dat er geen artikelen zijn aangeboden ter publicatie')
            ->columns($this->registerTableColumns())
            ->filters($this->registerTableFilters())
            ->recordActions($this->registerRecordActions());
    }

    /**
     * @return array<IconColumn|TextColumn|UserColumn>
     */
    private function registerTableColumns(): array
    {
        return [
            UserColumn::make('author_id')
                ->searchable()
                ->sortable()
                ->description(fn (Article $article): string => "{$article->author->firstname} {$article->author->lastname}")
                ->emptyStateHeading(config('app.name', 'Laravel')) // Custom empty state heading
                ->emptyStateDescription(fn (Article $article): ?string => $article->contributor_name ?? 'Anonieme gebruiker')
                ->label('Redacteur'),

            TextColumn::make('origin')
                ->label('Oorsprong')
                ->badge()
                ->sortable()
                ->toggleable()
                ->toggledHiddenByDefault(),

            TextColumn::make('word')
                ->label('Lemma')
                ->sortable()
                ->searchable()
                ->weight(FontWeight::Bold)
                ->color('primary'),

            TextColumn::make('description')
                ->label('Omschrijving')
                ->limit(75)
                ->color('gray'),

            IconColumn::make('has_media')
                ->label('Media')
                ->boolean()
                ->state(fn (Article $record): bool => ! empty($record->image_url))
                ->trueIcon('heroicon-o-photo')
                ->falseIcon('heroicon-o-x-circle')
                ->color(fn (bool $state): string => $state ? 'success' : 'gray'),

            IconColumn::make('seo_check')
                ->label('SEO')
                ->boolean()
                ->state(fn (Article $record): bool => ! empty($record->keywords) && ! empty($record->image_alt))
                ->trueColor('success')
                ->falseColor('warning')
                ->tooltip('Checkt op keywords en alt-teksten'),

            TextColumn::make('updated_at')
                ->label('Laatste wijziging')
                ->since()
                ->color('gray'),
        ];
    }

    /**
     * @return Filter[]
     */
    private function registerTableFilters(): array
    {
        return [
            Filter::make('image_url')
                ->label('Bevat een afbeelding')
                ->query(fn (Builder $query): Builder => $query->whereNotNull('image_url'))
                ->indicator('Bevat een afbeelding'),

            Filter::make('missing_seo')
                ->label('SEO Incompleet')
                ->query(fn (Builder $query): Builder => $query->whereNull('keywords')->orWhereNull('image_alt'))
                ->indicator('SEO Incompleet'),
        ];
    }

    /**
     * @return Action[]
     */
    private function registerRecordActions(): array
    {
        return [
            Action::make('view')
                ->icon(Heroicon::OutlinedEye)
                ->label('Bekijken')
                ->color('gray')
                ->url(fn (Article $article): string => ArticleResource::getUrl('view', ['record' => $article]))
        ];
    }
}
