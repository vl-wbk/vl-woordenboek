<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\RelationManagers;

use App\Features\DocumentationButtons;
use App\Filament\Clusters\Blog\Resources\BlogResource\Pages\ViewBlog;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Pennant\Feature;

final class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewBlog::class;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema(components: [
                TextEntry::make('commentator.name')
                    ->label('Gebruiker')
                    ->translateLabel()
                    ->color('primary')
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->columnSpan(7),
                TextEntry::make('created_at')
                    ->label('Schreef op')
                    ->icon('heroicon-o-clock')
                    ->columnSpan(5)
                    ->iconColor('primary'),
                TextEntry::make('comment')
                    ->label('Schreef het volgende als reactie')
                    ->translateLabel()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Reacties')
            ->emptyStateIcon('heroicon-o-chat-bubble-bottom-center-text')
            ->emptyStateHeading('Geen reacties toegevoegd')
            ->emptyStateDescription('Het lijkt erop dat er nog geen reacties zijn toegevoegd door gebruikers onder het nieuwsartikel. Kom later nog eens terug!')
            ->description('Een overzicht van alles reacties die geplaatst zijn onder het nieuwsartikel.')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('commentator'))
            ->columns(components: $this->getTableColumnComponents())
            ->headerActions(actions: $this->getHeaderActions())
            ->actions(actions: $this->getRowActions())
            ->bulkActions(actions: $this->getBulkTableActions());
    }

    protected function getHeaderActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('documentatie')
                    ->icon('heroicon-s-book-open')
                    ->label('documentatie'),
                Tables\Actions\Action::make('moderatie-faq')
                    ->icon('heroicon-s-document-text')
                    ->label('moderatie FAQ')
            ])
                ->visible(Feature::active(DocumentationButtons::class))
                ->button()
                ->icon('heroicon-o-lifebuoy')
                ->color('gray')
                ->label('Help')
        ];
    }

    protected function getBulkTableActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }

    protected function getRowActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->modalHeading('Reactie informatie')
                ->modalIcon('heroicon-o-chat-bubble-bottom-center-text'),

            Tables\Actions\DeleteAction::make()
                ->modalHeading('Reactie verwijderen'),
        ];
    }

    protected function getTableColumnComponents(): array
    {
        return [
            TextColumn::make('commentator.name')
                ->label('Gebruiker')
                ->translateLabel()
                ->sortable()
                ->icon('heroicon-o-user-circle')
                ->weight(FontWeight::SemiBold)
                ->color('primary')
                ->searchable(),
            TextColumn::make('comment')
                ->label('Reactie')
                ->translateLabel(),
            TextColumn::make('created_at')
                ->label('Toegevoegd op')
                ->translateLabel()
                ->sortable()
                ->date(),
        ];
    }
}
