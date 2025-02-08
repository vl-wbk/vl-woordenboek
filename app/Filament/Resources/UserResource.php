<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\UserTypes;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $pluralModelLabel = 'gebruikers';

    protected static ?string $modelLabel = 'gebruiker';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Nieuwe gebruiker aanmaken')
                    ->icon('heroicon-o-user-plus')
                    ->iconColor('primary')
                    ->description('Vul hier alle benodigde informatie in voor het aanmaken van een nieuwe gebruiker op het Vlaams woordenboek')
                    ->compact()
                    ->columns(12)
                    ->schema([
                        Select::make('user_type')
                            ->label('Gebruikersgroep')
                            ->required()
                            ->native(false)
                            ->options(UserTypes::class)
                            ->columnSpan(3),
                        TextInput::make('firstname')
                            ->label('Voornaam')
                            ->required()
                            ->columnSpan(4),
                        TextInput::make('lastname')
                            ->label('Achternaam')
                            ->required()
                            ->columnSpan(5),
                        TextInput::make('email')
                            ->label('E-mail adres')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->columnSpan(12)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Naam')
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user_type')
                    ->label('Gebruikersgroep')
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
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
