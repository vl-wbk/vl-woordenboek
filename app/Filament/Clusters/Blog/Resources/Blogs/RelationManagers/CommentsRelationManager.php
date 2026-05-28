<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\Column;
use App\Features\DocumentationButtons;
use App\Filament\Clusters\Blog\Resources\Blogs\Pages\ViewBlog;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Pennant\Feature;

/**
 * Managers the comments relationship for a blog post within a Filament Panel.
 *
 * This relation manager provides a dedicated table for viewing, a modal for inspecting, and actions for moderating comments.;
 * It is specifically designed to be attached to a blog resource and appear on the page for a single blog post.
 *
 * @package App\Filament\Clusters\Blog\Resources\BlogResource\RelationManagers
 */
final class CommentsRelationManager extends RelationManager
{
    /**
     * The name of the relationship on the parenbt model (Blog).
     * This static property tells Filament which Eloquent relationship to load when displaying this relation manager.
     */
    protected static string $relationship = 'comments';

    /**
     * Determines if the relation manager is read-only.
     *
     * By returning 'false', we allow users to interact with comments, such as viewing their details or deleting them.;
     * A 'true' value would disable all action on the table of the relation manager.
     *
     * @return bool false, as comment records are intended to be editable for the user who holds the correct permission(s).
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Checks if this relation manager should be displayed for a given record and page.
     * This method ensures the comments table is only visible when viewing a blog post's details (`ViewBlog::class`), and not on pages like `EditBlog` or `CreateBlog`.
     *
     * @param  Model    $ownerRecord  The parent model instance (the Blog post).
     * @param  string  $pageClass     The class name of the current Filament page.
     * @return bool                   True if the current page is `ViewBlog::class`, otherwise false.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewBlog::class;
    }

    /**
     * Defines the infolist schema for viewing a single comment's details.
     *
     * This infolist is displayed in a modal when the 'ViewAction' is triggered.
     * It uses a 12-column grid layout to present information clearly.
     *
     * @param \Filament\Schemas\Schema $schema The infolist builder instance.
     * @return \Filament\Schemas\Schema The configured infolist with its schema components.
     */
    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components(components: [
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

    /**
     * Defines the table schema and configuration for listing comments.
     * This method customizes the table's appearance and behavior, including its  heading, empty state message, description, and the eager loading of the `commentator` relationship to optimize performance.
     *
     * @param  Table  $table  The table builder instance.
     * @return Table          The fully configured table for comments.
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Reacties')
            ->emptyStateIcon('heroicon-o-chat-bubble-bottom-center-text')
            ->emptyStateHeading('Geen reacties toegevoegd')
            ->emptyStateDescription('Het lijkt erop dat er nog geen reacties zijn toegevoegd door gebruikers onder het nieuwsartikel. Kom later nog eens terug!')
            ->description('Een overzicht van alles reacties die geplaatst zijn onder het nieuwsartikel.')
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->with('commentator'))
            ->columns(components: $this->getTableColumnComponents())
            ->headerActions(actions: $this->getHeaderActions())
            ->recordActions(actions: $this->getRowActions())
            ->toolbarActions(actions: $this->getBulkTableActions());
    }

    /**
     * Defines the header actions for the comments table.
     * This method adds a conditional "Help" action group that is only visible if the `DocumentationButtons` feature is active in the application.
     *
     * @return array<int, ActionGroup> An array of table header actions.
     */
    private function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('documentatie')
                    ->icon('heroicon-s-book-open')
                    ->label('documentatie'),
                Action::make('moderatie-faq')
                    ->icon('heroicon-s-document-text')
                    ->label('moderatie FAQ'),
            ])
                ->visible(Feature::active(DocumentationButtons::class))
                ->button()
                ->icon('heroicon-o-lifebuoy')
                ->color('gray')
                ->label('Help'),
        ];
    }

    /**
     * Defines the bulk actions that can be performed on multiple selected comments.
     * Currently, this only includes the standard `DeleteBulkAction`, allowing users to delete multiple comments simultaneously.
     *
     * @return array<int, \Filament\Actions\BulkActionGroup> An array of bulk actions.
     */
    private function getBulkTableActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }

    /**
     * Defines the actions available for each individual row in the table.
     * This provides quick access to view the comment details in a modal and to delete the comment.
     *
     * @return array<int, ViewAction|DeleteAction> An array of row actions.
     */
    private function getRowActions(): array
    {
        return [
            ViewAction::make()
                ->modalHeading('Reactie informatie')
                ->modalIcon('heroicon-o-chat-bubble-bottom-center-text'),

            DeleteAction::make()
                ->modalHeading('Reactie verwijderen'),
        ];
    }

    /**
     * Defines the table column components.
     * This method configures the columns that are displayed in the comments table, including their labels, icons, sorting, and searchability.
     *
     * @return array<int, Column> An array of table column components.
     */
    private function getTableColumnComponents(): array
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
