<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\RelationManagers;

use App\Enums\Articles\InsightCategory;
use App\Filament\Resources\Articles\Pages\ViewWord;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\Reaction;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class ReactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'reactions';

    protected static ?string $title = 'Community inzichten';

    private static string $defaultColor = 'primary';

    protected static \BackedEnum|null|string $icon = 'heroicon-o-tag';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return Cache::rememberForever('community-insights:' . $ownerRecord->getRouteKey(), function () use ($ownerRecord): string {
            return (string) $ownerRecord->reactions()->count();
        });
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass() instanceof ViewWord && self::getBadge($ownerRecord, $pageClass) > 0;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Community inzichten')
            ->description('Een overzicht van alle oude reacties die gekoppeld waren aan het artikel in de oude versie van het Vlaams Woordenboek. Deze kunnen nuttig zijn voor verdere verfijning van het artikel in kwestie.')
            ->emptyStateIcon(Heroicon::OutlinedChatBubbleLeftRight)
            ->emptyStateHeading('Geen reacties gevonden')
            ->emptyStateDescription('Het lijkt erop dat in de vorige versie van het Vlaams Woordenboek die artikel geen reacties had of er geen matchen mat de opgegeven criteria.')
            ->columns($this->getTableColumnSchema())
            ->filters($this->getFilters())
            ->recordActions($this->getTableRecordActions());
    }

    private function getFilters(): array
    {
        return [
            SelectFilter::make('insight_category')
                ->native(false)
                ->options(InsightCategory::class),
        ];
    }

    private function getTableColumnSchema(): array
    {
        return [
            TextColumn::make('id')
                ->weight(FontWeight::Bold)
                ->color(self::$defaultColor)
                ->label('#'),
            TextColumn::make('insight_category')
                ->label('Categorisering')
                ->icon(Heroicon::OutlinedListBullet)
                ->badge(),
            TextColumn::make('author')
                ->label('Ingevoegd door'),
            TextColumn::make('title')
                ->label('Titel')
                ->searchable(),
            TextColumn::make('created_at')
                ->label('Geplaatst op')
                ->sortable()
                ->date(),
        ];
    }

    private function getTableRecordActions(): array
    {
        return [
            ViewAction::make()
                ->modalIcon(Heroicon::OutlinedChatBubbleLeftRight)
                ->modalIconColor(self::$defaultColor)
                ->modalHeading(fn (Reaction $reaction): ?string => $reaction->title)
                ->modalDescription(fn (Reaction $reaction): string => __('Geplaatst door :user op :date', ['user' => $reaction->author, 'date' => $reaction->created_at->format('d F Y')]))
                ->modalCloseButton(false)
                ->schema([
                    TextEntry::make('insight_category')
                        ->hintAction($this->getHintActions())
                        ->label('Categorisering:')
                        ->formatStateUsing(fn(Reaction $reaction): string => $reaction->insight_category->getFullDisplayLabel())
                        ->columnSpanFull(),
                    TextEntry::make('body')
                        ->label('Reactie:')
                ]),

            EditAction::make()
        ];
    }

    private function getHintActions(): Action
    {
        return Action::make('aanpassen')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->authorize('update')
            ->modalHeading('Reactie categoriseren')
            ->modalDescription('U staat op een punt om een reactie te categoriseren. Het categoriseren van een reactie is niet vereist, maar het kan helpen bij de verrijking van artikelen.')
            ->modalWidth(Width::Medium)
            ->modalAlignment(Alignment::Center)
            ->modalIcon(Heroicon::Tag)
            ->modalIconColor(self::$defaultColor)
            ->badgeTooltip('test')
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalCancelAction(false)
            ->modalSubmitActionLabel('uitvoeren')
            ->action(fn (Reaction $reaction, array $data): bool => $reaction->update(['insight_category' => $data['insight_category']]))
            ->schema([
                Select::make('insight_category')
                    ->label('Insight categorie')
                    ->required()
                    ->native(false)
                    ->options(InsightCategory::class)
            ]);
    }
}
