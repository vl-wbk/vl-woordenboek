<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers;

use App\Enums\Articles\EtymologyStatus;
use App\Enums\Articles\EtymologyTypes;
use App\Filament\Resources\ArticleResource\Pages\ViewWord;
use App\Models\Article;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

final class EtymologyRelationManager extends RelationManager
{
    protected static string $relationship = 'etymology';

    protected static ?string $icon = 'heroicon-o-clock';

    protected static ?string $title = 'Etymologie';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('De etymologie beschrijft de herkomst en geschiedenis van een woord. In deze sectie ontdek je hoe een woord is ontstaan, uit welke taal het is overgenomen, en hoe het zich in de loop van de tijd heeft ontwikkeld. We verwijzen daarbij naar verwante vormen in andere talen, historische spellingswijzen en oorspronkelijke betekenissen. Zo krijg je inzicht in de wortels van het woord en de weg die het heeft afgelegd naar het huidige gebruik in het Nederlands.')
            ->emptyStateIcon(self::$icon)
            ->emptyStateHeading('Geen gegevens gevonden')
            ->emptyStateDescription('Er zijn geen gevens gevonden voor de etymologie van het woord')
            ->bulkActions($this->configureBulkActions())
            ->headerActions($this->configureHeaderActions())
            ->columns($this->configureColumns())
            ->filters($this->configureFilters())
            ->filtersFormWidth(MaxWidth::Medium)
            ->actions($this->configureHandlings());
    }

    private function configureFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->options(EtymologyStatus::class)
                ->default(EtymologyStatus::UnderReview->value)
                ->native(false)
        ];
    }

    private function configureColumns(): array
    {
        return [
            TextColumn::make('period')
                ->label('Periode')
                ->sortable(),
            TextColumn::make('status')
                ->toggleable(isToggledHiddenByDefault: true)
                ->badge(),
            TextColumn::make('type')
                ->label('Woordsoort')
                ->sortable()
                ->badge(),
            TextColumn::make('origin_language')
                ->label('Oorspronkelijke taal')
                ->translateLabel(),
            TextColumn::make('origin_form')
                ->label('Woordvorm')
                ->searchable()
                ->sortable(),
            TextColumn::make('etymology')
                ->label('Beschrijving')
                ->limit()
                ->translateLabel(),
            TextColumn::make('source')
                ->label('Bron')
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')
                ->sortable()
                ->label('Aangemaakt op')
                ->translateLabel()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->sortable()
                ->label('Laast gewijzigd')
                ->translateLabel()
                ->date()
                ->toggleable(isToggledHiddenByDefault: true)
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Select::make('status')
                    ->label('Status van de gegevens')
                    ->translateLabel()
                    ->columnSpan(3)
                    ->options(EtymologyStatus::class)
                    ->native(false)
                    ->required(),

                Select::make('type')
                    ->label('Etymologisch type')
                    ->translateLabel()
                    ->options(EtymologyTypes::class)
                    ->required()
                    ->searchable()
                    ->native(false)
                    ->columnSpan(3),

                TextInput::make('origin_language')
                    ->label('Taal van oorsprong')
                    ->translateLabel()
                    ->columnSpan(3)
                    ->required()
                    ->maxLength(255),

                TextInput::make('origin_form')
                    ->label('Vorm in de brontaal')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),

                DatePicker::make('period_start')
                    ->label('Periode (start)')
                    ->translateLabel()
                    ->required()
                    ->native(false)
                    ->columnSpan(6),

                DatePicker::make('period_end')
                    ->label('Periode (einde)')
                    ->translateLabel()
                    ->required()
                    ->native(false)
                    ->columnSpan(6),

                Textarea::make('etymology')
                    ->label('Beschrijving van de herkomst')
                    ->translateLabel()
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('note')
                    ->label('Interne notitie voor administratieve doeleinden')
                    ->translateLabel()
                    ->columnSpanFull(),

                TextInput::make('source')
                    ->label('Bron notitie')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),

                TextInput::make('source_url')
                    ->label('Hyperlink van de bron')
                    ->translateLabel()
                    ->maxLength(255)
                    ->columnSpan(6),
            ]);
    }

    private function configureHandlings(): array
    {
        return [
            ActionGroup::make([
                ViewAction::make()
                    ->modalHeading('Etymologische gegevens bekijken')
                    ->modalIcon('heroicon-o-eye')
                    ->modalIconColor('primary')
                    ->modalDescription('Alle geregistreerde gegevens omtrent de etymologie van het woord'),
                DeleteAction::make()
                    ->modalHeading('Etymolische gegevens verwijderen'),
            ])
        ];
    }

    protected function configureHeaderActions(): array
    {
        return [
            Tables\Actions\Action::make('help')
                ->label('Help')
                ->translateLabel()
                ->icon('heroicon-o-lifebuoy')
                ->url('https://www.google.com', shouldOpenInNewTab: true)
                ->color('gray'),

            Tables\Actions\CreateAction::make('create-record')
                ->label('Gegevens toevoegen')
                ->translateLabel()
                ->icon('heroicon-o-pencil-square')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalWidth(MaxWidth::SevenExtraLarge)
                ->modalHeading('Etymologische gegevens toevoegen')
                ->modalDescription('U staat op het punt om etymologische gegevens toe te voegen voor het woord ' . $this->ownerRecord->word),
        ];
    }

    protected function configureBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make()
                ->modalHeading('Etymologische gegevens verwijderen')
                ->modalDescription('U staat op het punt om etymologische gegevens te verwijderen. Ben u zeker deze actie te willen uitvoeren?')
                ->modalSubmitActionLabel('Ja, ik ben zeker')
        ];
    }
}
