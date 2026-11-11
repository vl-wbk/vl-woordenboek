<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Users\Pages\ViewUser;
use App\Models\User;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Override;

final class FeatureBansRelationManager extends RelationManager
{
    protected static string $relationship = 'featureBans';

    protected static ?string $title = 'Beperkingen';

    protected static string|BackedEnum|null $icon = 'heroicon-o-no-symbol';

    #[Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Whether the relation manager can view the relation for a record.
     *
     * @param Model $ownerRecord
     * @param string $pageClass
     * @return bool
     */
    #[Override]
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewUser::class;
    }

    /**
     * The form schema for the relation manager.
     *
     * @param Schema $schema
     * @return Schema
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Select::make('feature')
                    ->label('Functionaliteit')
                    ->options(config('flemish-dictionary.features.bannable', []))
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn ($rule) => $rule->where('user_id', $this->getOwnerRecord()->id))
                    ->required()
                    ->native(false)
                    ->columnSpan(6),

                DateTimePicker::make('expires_at')
                    ->label('Verloopt op')
                    ->columnSpan(6)
                    ->helperText('Laat dit leeg indien u een permanente ban wenst')
                    ->after('now'),
                    
                Textarea::make('reason')
                    ->label('Motivatie')
                    ->rows(5)
                    ->placeholder('Beschrijf kort waarom de gebruiker beperkt word in zijn functionaliteiten.')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    /**
     * The table schema for the relation manager.
     *
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Functionaliteits beperkingen')
            ->emptyStateHeading('Geen beperkingen gevonden')
            ->emptyStateDescription('Het lijkt erop dat de gebruiker geen actieve beperkingen heeft.')
            ->description('Een overzicht van alle actieve functionaliteits beperkingen voor de gebruiker')
            ->recordTitleAttribute('feature')
            ->columns([
            TextColumn::make('feature')
                ->label('Functionaliteit')
                ->badge()
                ->color('danger')
                ->formatStateUsing(fn ($state) => config("flemish-dictionary.features.bannable.$state", $state)),

            TextColumn::make('reason')
                ->label('Motivatie')
                ->limit(40),
            
            TextColumn::make('expires_at')
                ->dateTime()
                ->placeholder('permanent'),

            TextColumn::make('created_at')
                ->since(),
        ]);
    }
}

