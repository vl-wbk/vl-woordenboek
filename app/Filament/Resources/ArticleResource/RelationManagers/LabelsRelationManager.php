<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\RelationManagers;

use App\Filament\Clusters\Articles\Resources\LabelResource;
use App\Filament\Resources\ArticleResource\Pages\ViewWord;
use App\Models\Label;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class LabelsRelationManager
 *
 * Manages the relationshop^ bewteen articles and lebels within the Filament admin panel.
 * This relation manager allows users to view, attach, and detach labels associated with an dictionary article.
 *
 * The class defines the form structure, table layout, and available actions for managing labels.
 * Labels can be created directly or attached from existing ones, and the table provides an overview
 * of all linked labels, including their names, descriptions, and attachment dates.
 *
 * This relation manager is only accessible form the "ViewRecord" page to ensure labels are managed
 * Within the context of viewing an article.
 */
final class LabelsRelationManager extends RelationManager
{
    /**
     * Defines the relationship name for the labels on the article model.
     */
    protected static string $relationship = 'labels';

    /**
     * Controls whether the label realtionship is visible on a specific page.
     * It ensures that labels are only shown when viewing an article through the 'ViewWord' page.
     *
     * @param  Model  $ownerRecord  The related article model.
     * @param  string  $pageClass  The class-strintg of the current Filament page of the dictionary article.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    /**
     * Returns the form configuration for creating and editing labels.
     * The form setup is delegated to LabelResource to maintain consistency across the application.
     *
     * @param  Form  $form  The filament form instance.
     */
    public function form(Form $form): Form
    {
        return LabelResource::form($form);
    }

    /**
     * Determines if the relation manager should be read-only.
     * Returns false, aloowing users to modify label assignments on the edit section of the dictionary article.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Defines the table configuration for displaying labels associated with an article.
     * The table includes columns for label name, description, and the date of attachment,
     * along with actions to manage labels  such as viewing, creating and detaching them.
     *
     * @param  Table  $table  The Filament table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Gekoppelde labels')
            ->description('Overzicht van alle gekoppelde labels aan het woord.')
            ->emptyStateHeading('Geen label gevonden')
            ->emptyStateIcon('heroicon-o-tag')
            ->emptyStateDescription('Momenteel zijn er geen labels gekoppeld aan het artikel gebruik de bovenstaande knop om een label te koppelen')
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Beschrijving')
                    ->placeholder('- geen beschrijving opgegeven')
                    ->formatStateUsing(fn (Label $label): string => Str::limit($label->description, 60, '...', preserveWords: true)),
                Tables\Columns\TextColumn::make('pivot.created_at')
                    ->label('Gekoppeld op')
                    ->date()
                    ->sortable(),
            ])
            ->headerActions([
                $this->getCreateAction(),
                $this->getHeaderAttachAction(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Configures the action for creating a new label.
     * This action opens a modal allowing users to define a ne label, which will be automatically atteched to the article upon creation.
     */
    private function getCreateAction(): CreateAction
    {
        return CreateAction::make()
            ->modalIcon('heroicon-o-plus')
            ->modalIconColor('gray')
            ->createAnother(false)
            ->modalDescription('Na het aanmaken van een label zal deze automatisch aan heb woordenboek artikel worden gekoppeld.')
            ->icon('heroicon-o-plus');
    }

    /**
     * Configures the action for attaching an existing label.
     * This action allows users to select and attach multiple labels to an article, presented in a larger modal for better usability.
     */
    private function getHeaderAttachAction(): AttachAction
    {
        return AttachAction::make()
            ->modalWidth(MaxWidth::TwoExtraLarge)  // S
            ->modalIcon('heroicon-o-link')
            ->modalIconColor('gray')
            ->attachAnother(false)
            ->multiple()
            ->preloadRecordSelect()
            ->modalAutofocus(false)
            ->color('gray')
            ->icon('heroicon-o-link')
            ->label('Labels koppelen');
    }
}
