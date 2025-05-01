<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use App\Enums\DisclaimerTypes;
use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\DisclaimerResource\Pages;
use App\Models\Disclaimer;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

final class DisclaimerResource extends Resource
{
    protected static ?string $model = Disclaimer::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $cluster = Articles::class;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Section::make('Disclaimer informatie')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->iconSize(IconSize::Medium)
                    ->iconColor('primary')
                    ->description('Alle gegevens en configuratie die gebruikt zal worden om de disclaimer te tonen aan de eindgebruiker die het Vlaams woordenboek raadpleegt.')
                    ->collapsible()
                    ->columns(12)
                    ->compact()
                    ->schema([
                        Select::make('type')
                            ->columnSpan(6)
                            ->required()
                            ->options(DisclaimerTypes::class)
                            ->native(false),
                        Textarea::make('message')
                            ->label('Disclaimer melding')
                            ->required()
                            ->placeholder('Vermeld kort wat je wenst te vermelding richting de gebruiker')
                            ->columnSpan(12)
                            ->rows(2),
                    ]),

                Section::make('Beheersinformatie')
                    ->description('De nodige registraties van interne gegevens die ons toelaat de disclaimers te beheren en te vermelden hoe we de geregistreerde disclaimer wensen te gebruiken.')
                    ->icon('heroicon-o-information-circle')
                    ->compact()
                    ->iconSize(IconSize::Medium)
                    ->iconColor('primary')
                    ->columns(12)
                    ->collapsible()
                    ->schema([
                        TextInput::make('name')
                            ->label('Naam')
                            ->maxLength(255)
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(8),
                        Textarea::make('description')
                            ->label('Beschrijving')
                            ->required()
                            ->placeholder('Beschrijf kort waarover de disclaimer gaat zodat het duidelijk is voor andere vrijwilligers')
                            ->columnSpan(12)
                            ->rows(3),
                        Textarea::make('usage')
                            ->label('Gebruikscriteria')
                            ->required()
                            ->placeholder('Beschrijf kort in welke omstandigheden de disclaimer te gebruiken is')
                            ->columnSpan(12)
                            ->rows(3)
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                Tabs::make('Information tabs')
                    ->columnSpan(12)
                    ->schema([
                        Tab::make('Disclaimer informatie')
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->columns(12)
                            ->schema([
                                TextEntry::make('type')
                                    ->badge()
                                    ->columnSpan(4)
                                    ->label('Disclaimer type'),
                                TextEntry::make('name')
                                    ->columnSpan(8)
                                    ->label('Naam van de disclaimer'),
                                TextEntry::make('message')
                                    ->label('Melding')
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Interne beschrijving')
                            ->columns(12)
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextEntry::make('description')
                                    ->columnSpan(12)
                                    ->hiddenLabel(),
                            ]),
                        Tab::make('Gebruiksrichtlijn')
                            ->columns(12)
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextEntry::make('usage')
                                    ->hiddenLabel()
                                    ->columnSpan(12),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Disclaimers')
            ->description('Disclaimers zijn bedoeld om gebruikers snel extra informatie te geven.')
            ->emptyStateIcon(self::$navigationIcon)
            ->headerActions([
                Action::make('help')
                    ->label('Help')
                    ->icon('heroicon-o-lifebuoy')
                    ->color('gray'),
                CreateAction::make()
                    ->label('Disclaimer aanmaken')
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->emptyStateHeading('Geen disclaimer(s) aangemaakt')
            ->emptyStateDescription('Momenteel zijn er geen disclaimers aangemaakt en of gevonden onder de matchende de gegeven criteria.')
            ->columns([
                TextColumn::make('name')
                    ->label('naam')
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('articles_count')
                    ->counts('articles')
                    ->sortable()
                    ->label('aantal koppelingen'),
                TextColumn::make('description')
                    ->label('beschrijving')
                    ->words(12)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->label('aangemaakt op')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel()
                    ->tooltip('bekijken'),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->tooltip('bewerken'),
                Tables\Actions\DeleteAction::make()
                    ->modalDescription('U staat op het punt om een disclaimer te verwijderen. Bij het verwijderen zal deze worden losgekoppeld van alle artikelen. Weet u zeker dat je dit wilt doen?')
                    ->hiddenLabel()
                    ->tooltip('verwijderen'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDisclaimers::route('/'),
            'create' => Pages\CreateDisclaimer::route('/create'),
            'view' => Pages\ViewDisclaimer::route('/{record}'),
            'edit' => Pages\EditDisclaimer::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('disclaimer_count', [10, 60], function (): string {
            return (string) self::$model::count();
        });
    }
}
