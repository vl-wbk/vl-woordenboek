<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Schema;

use App\Filament\Resources\UserResource;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Gate;
use App\Filament\Resources\UserResource\Actions;
use App\UserTypes;
use App\Models\User;

final readonly class UserTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Gebruikersbeheer')
            ->description('In dit overzicht zie je alle geregistreerde gebruikers van het systeem. Je kunt hier gebruikersgegevens bekijken, accounts bewerken, rollen toewijzen of gebruikers verwijderen. Gebruik de zoek- en filteropties om snel de juiste gebruiker te vinden.')
            ->headerActions([
                Action::make('documentation-reference')
                    ->color('gray')
                    ->icon('heroicon-o-book-open')
                    ->label('Help'),
                CreateAction::make()
                    ->label('Gebruiker toevoegen')
                    ->icon('heroicon-o-user-plus')
            ])
            ->recordUrl(fn (User $user): string => UserResource::getUrl('view', ['record' => $user]))
            ->columns([
                TextColumn::make('name')
                    ->iconColor('danger')
                    ->icon(fn (User $user): ?string  => $user->isBanned() ? 'tabler-shield-lock' : null)
                    ->label('Naam')
                    ->weight(FontWeight::Bold)
                    ->color(fn (User $user): string => $user->isBanned() ? 'danger' : 'primary')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user_type')
                    ->label('Gebruikers rol')
                    ->badge(),
                TextColumn::make('email')
                    ->label('E-mail adres')
                    ->searchable()
                    ->url(fn (User $user): string => 'mailto:' . $user->email),
                TextColumn::make('last_seen_at')
                    ->placeholder('-')
                    ->sortable()
                    ->since()
                    ->label('Laatste aanmelding'),
                TextColumn::make('created_at')
                    ->sortable()
                    ->label('Registratie tijdstip')
            ])
            ->filters([
                SelectFilter::make('user_type')
                    ->label('Gebruikersgroep')
                    ->native(false)
                    ->options(UserTypes::class),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    // Custom actions for activating/deactivating user accounts in the application platform.
                    Actions\BanAction::make()->visible(fn (User $user): bool => Gate::allows('deactivate', $user)),
                    Actions\UnbanAction::make()->authorize(fn (User $user): bool => Gate::allows('reactivate', $user)),

                    // Default delete actions
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
