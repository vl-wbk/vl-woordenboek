<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Models\Feedback;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

/**
 * @todo Document this class
 * @todo Split the methods up in their own schema's
 * @todo Provide additional end user documentation
 */
final class FeedbackResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getPermissionPrefixes(): array
    {
        return ['view_any', 'view', 'delete', 'delete_any'];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('Ingestuurd door')
                    ->columns(12)
                    ->schema([
                        TextEntry::make('name')
                            ->weight(FontWeight::SemiBold)
                            ->icon('heroicon-o-user-circle')
                            ->iconColor('primary')
                            ->columnSpan(6)
                            ->hiddenLabel(),
                        TextEntry::make('email')
                            ->columnSpan(6)
                            ->icon('heroicon-o-envelope')
                            ->iconColor('primary')
                            ->hiddenLabel(),
                    ]),
                Fieldset::make('Feedback')
                    ->columns(12)
                    ->schema([
                        TextEntry::make('first_time_visit')
                            ->badge()
                            ->label('Eerste bezoek')
                            ->columnSpan(4),
                        TextEntry::make('results_found_easily')
                            ->badge()
                            ->label('Kon gemakelijk resultaten bekomen')
                            ->columnSpan(4),
                        IconEntry::make('contact_allowed')
                            ->label('Mag gecontacteerd worden')
                            ->boolean()
                            ->columnSpan(4),
                        TextEntry::make('visit_reason')
                            ->label('Reden van het bezoek aan het Vlaams woordenboek')
                            ->columnSpan(12)
                            ->placeholder('- Niet opgegeven'),
                        TextEntry::make('search_additional_info')
                            ->label('Wat er volgens de gebruiker beter kon tijdens het zoeken naar artikelen')
                            ->columnSpanFull()
                            ->placeholder('- Niet opgegeven'),
                        TextEntry::make('additional_info')
                            ->label('Extra info / Suggestie(s) van de gebruiker')
                            ->columnSpanFull()
                            ->placeholder('- Niet ingevuld'),
                    ]),
            ]);
    }

    public static function getWidgets(): array
    {
        /** @phpstan-ignore-next-line */
        return [
            \App\Filament\Resources\FeedbackResource\Widgets\FeedbackStatisticsWidget::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Ingezonden feedback')
            ->headerActions([
                ActionsAction::make('documentation')
                    ->color('primary')
                    ->label('Documentatie')
                    ->url('https://www.google.com', shouldOpenInNewTab: true),
            ])
            ->description('Een overzicht van alle feedback of bugs die zijn ingezonden door gebruikers van het Vlaams Woordenboek')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen feedback ontvangen')
            ->emptyStateDescription('Momenteel is er nog geen feedback ingestuurd door gebruikers van het Vlmaams woordenboek. Kom later nog eens terug.')
            ->deferLoading()
            ->columns([
                TextColumn::make('tracking_number')
                    ->label('Volgnummer')
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->color('primary')
                    ->placeholder('-'),
                TextColumn::make('name')
                    ->label('Ingestuurd door')
                    ->iconColor('primary')
                    ->icon('heroicon-o-user-circle')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->placeholder('- niet opgegeven'),
                IconColumn::make('contact_allowed')
                    ->label('Contact toegelaten')
                    ->boolean(),
                TextColumn::make('first_time_visit')
                    ->label('Eerste bezoek')
                    ->badge()
                    ->sortable(),
                TextColumn::make('results_found_easily')
                    ->label('Resultaten gevonden?')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Ingestuurd op')
                    ->sortable()
                    ->date(),

            ])
            ->actions([
                self::viewAction(),
                self::deleteAction(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalDescription('Bij het verwijderen van de feedback kan het mogelijks zijn dat er waardevolle beedback verloren gaat. Alvorens de feedback te verwijderen wees er zeker van dat de personen die er baat bij hebben de feedback hebben gelezen.'),
                ]),
            ]);
    }

    public static function viewAction(): ViewAction
    {
        return ViewAction::make()
            ->slideOver()
            ->modalFooterActions([
                ActionsAction::make('Mail gebruiker')
                    ->color('gray')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(fn(Feedback $feedback): bool => $feedback->contact_allowed)
                    ->url(fn(Feedback $feedback): string => "mailto:{$feedback->email}"),
                self::deleteAction()
                    ->hiddenLabel(false),
            ])
            ->hiddenLabel()
            ->tooltip('Bekijken')
            ->modalIcon('heroicon-o-information-circle')
            ->modalIconColor('info')
            ->modalHeading(heading: function (Feedback $feedback): string {
                return $feedback->tracking_number ? "{$feedback->tracking_number}: Feedback informatie" : 'Feedback overzicht';
            })
            ->modalDescription(fn(Feedback $feedback): string => trans('Ingestuurd door :user op :date', ['user' => $feedback->name, 'date' => $feedback->created_at->format('d/m/Y')]));
    }

    public static function deleteAction(): DeleteAction
    {
        return DeleteAction::make()
            ->hiddenLabel()
            ->tooltip('Verwijderen')
            ->modalDescription('Bij het verwijderen van de feedback kan het zijn indien de onbehandeld is waardevolel informatie verloren gaat voor de verdere groei van het Vlaams Woordenboek, en vragen we je om deze handeling te bevestigen');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedback::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $feedbackCount = Cache::flexible('feedback_count', [10, 60], fn(): string => (string) self::$model::count());

        // Return the count if it's greater than 0, otherwise return null
        return $feedbackCount > 0 ? $feedbackCount : null;
    }
}
