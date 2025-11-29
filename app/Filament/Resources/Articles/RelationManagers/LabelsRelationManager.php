<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\RelationManagers;

use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DetachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\CreateAction;
use Filament\Actions\AttachAction;
use Filament\Support\Enums\Width;
use App\Models\Label;
use Illuminate\Support\Str;
use App\Filament\Clusters\Articles\Resources\Labels\LabelResource;
use App\Filament\Resources\Articles\Pages\ViewWord;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LabelsRelationManager
 *
 * Manages the relationship between articles and labels within the Filament admin panel.
 * This relation manager allows users to view, attach, and detach labels associated with an dictionary article.
 *
 * The class defines the form structure, table layout, and available actions for managing labels.
 * Labels can be created directly or attached from existing ones, and the table provides an overview
 * of all linked labels, including their names, descriptions, and attachment dates.
 *
 * This relation manager is only accessible form the "ViewRecord" page to ensure labels are managed
 * Within the context of viewing an article.
 *
 * @package App\Filament\Resources\ArticleResource\RelatyionManagers
 */
final class LabelsRelationManager extends RelationManager
{
    /**
     * Defines the name of the relationship method on the owner article model that this relation manager interacts with.
     * This property explicitly links the manager to the `labels` relationship defined within the `Article` model.
     */
    protected static string $relationship = 'labels';

    /**
     * Defines the icon to be displayed alongside the relation manager's tab or heading.
     * The 'heroicon-o-tag' icon visually represents the concept of tagging or labeling.
     */
    protected static string|BackedEnum|null $icon = "heroicon-o-tag";

    /**
     * Returns the form configuration for creating and editing labels.
     * The form setup is delegated to LabelResource to maintain consistency across the application.
     *
     * @param  Schema $schema  The filament form instance.
     * @return Schema          The configured form instance.
     */
    public function form(Schema $schema): Schema
    {
        return LabelResource::form($schema);
    }

    /**
     * Determines if the relation manager should be read-only.
     * Returns false, allowing users to modify label assignments on the edit section of the dictionary article.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Controls whether the label relationship is visible on a specific page.
     * It ensures that labels are only shown when viewing an article through the 'ViewWord' page.
     *
     * @param  Model   $ownerRecord  The related article model.
     * @param  string  $pageClass    The class-string of the current Filament page of the dictionary article.
     * @return bool                  Whether the user can view the relation manager or not.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    /**
     * Defines the table configuration for displaying labels associated with an article.
     * The table includes columns for label name, description, and the date of attachment,
     * along with actions to manage labels  such as viewing, creating and detaching them.
     *
     * @param  Table $table  The Filament table instance.
     * @return Table         The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading(heading: __('filament/RelationManagers/LabelsRelationManager.table.heading'))
            ->description(description: __('filament/RelationManagers/LabelsRelationManager.table.description'))
            ->emptyStateHeading(heading: __('filament/RelationManagers/LabelsRelationManager.empty-state.heading'))
            ->emptyStateIcon(icon: Heroicon::OutlinedTag)
            ->emptyStateDescription(description: __('filament/RelationManagers/LabelsRelationManager.empty-state.description'))
            ->recordTitleAttribute(attribute: 'name')
            ->columns(components: [
                TextColumn::make('name')
                    ->label(label: __('filament/RelationManagers/LabelsRelationManager.table.columns.name'))
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(label: __('filament/RelationManagers/LabelsRelationManager.table.columns.description'))
                    ->placeholder(placeholder:__('filament/RelationManagers/LabelsRelationManager.table.columns.description-placeholder'))
                    ->formatStateUsing(fn(Label $label): string => Str::limit($label->description, 60, preserveWords: true)),
                TextColumn::make('pivot.created_at')
                    ->label(label: __('filament/RelationManagers/LabelsRelationManager.table.columns.attached-at'))
                    ->date()
                    ->sortable(),
            ])
            ->headerActions([
                $this->getCreateAction(),
                $this->getHeaderAttachAction(),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Configures the action for creating a new label.
     * This action opens a modal allowing users to define a ne label, which will be automatically attached to the article upon creation.
     */
    private function getCreateAction(): CreateAction
    {
        return CreateAction::make()
            ->modalIcon(icon: Heroicon::OutlinedPlus)
            ->modalIconColor('gray')
            ->createAnother(false)
            ->modalDescription(description:__('filament/RelationManagers/LabelsRelationManager.actions.create.modal.description'))
            ->icon(icon: Heroicon::OutlinedPlus);
    }

    /**
     * Configures the action for attaching an existing label.
     * This action allows users to select and attach multiple labels to an article, presented in a larger modal for better usability.
     */
    private function getHeaderAttachAction(): AttachAction
    {
        return AttachAction::make()
            ->modalWidth(Width::TwoExtraLarge)  // S
            ->modalIcon(icon: Heroicon::OutlinedLink)
            ->modalIconColor('gray')
            ->attachAnother(false)
            ->multiple()
            ->preloadRecordSelect()
            ->modalAutofocus(false)
            ->color('gray')
            ->icon(icon: Heroicon::OutlinedLink)
            ->label(__('filament/RelationManagers/LabelsRelationManager.actions.attach.label'));
    }
}
