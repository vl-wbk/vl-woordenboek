<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schema;

use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use App\Filament\Resources\Users\Actions\BanAction;
use App\Filament\Resources\Users\Actions\UnbanAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Users\UserResource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
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
            ->heading(heading: __('user-resource.tables.heading'))
            ->description(description: __('user-resource.tables.description'))
            ->headerActions([
                Action::make('documentation-reference')
                    ->icon('heroicon-o-book-open')
                    ->label(label: __('buttons.help')),

                ActionGroup::make([
                    CreateAction::make()
                        ->label(label: __('user-resource.buttons.create-user'))
                        ->color('gray')
                        ->icon('heroicon-o-user-plus'),
                    FactoryAction::make()->color('gray')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedCog8Tooth)
                ])->buttonGroup(),
            ])
            ->recordUrl(fn(User $user): string => UserResource::getUrl('view', ['record' => $user]))
            ->columns([
                TextColumn::make('name')
                    ->iconColor('danger')
                    ->icon(fn(User $user): ?string => $user->isBanned() ? 'tabler-shield-lock' : null)
                    ->label(label: __('user-resource.tables.columns.name'))
                    ->label('Gebruikersnaam')
                    ->placeholder('-')
                    ->weight(FontWeight::Bold)
                    ->color(fn(User $user): string => $user->isBanned() ? 'danger' : 'primary')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user_type')
                    ->label(label: __('user-resource.tables.columns.user-type'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label(label: __('user-resource.tables.columns.roles.label'))
                    ->icon('heroicon-o-key')
                    ->placeholder(placeholder: __('user-resource.tables.columns.roles.placeholder'))
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge(),

                TextColumn::make('email')
                    ->label(label: __('user-resource.tables.columns.email'))
                    ->searchable()
                    ->url(fn(User $user): string => 'mailto:' . $user->email),
                TextColumn::make('last_seen_at')
                    ->placeholder('-')
                    ->sortable()
                    ->since()
                    ->label(label: __('user-resource.tables.columns.last-seen-at')),
                TextColumn::make('created_at')
                    ->sortable()
                    ->label('Registratie tijdstip'),
            ])
            ->filters([
                SelectFilter::make('user_type')
                    ->label(label: __('user-resource.tables.filters.user-type'))
                    ->native(false)
                    ->options(UserTypes::class),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),

                    // Custom actions for activating/deactivating user accounts in the application platform.
                    BanAction::make()->visible(fn(User $user): bool => Gate::allows('deactivate', $user)),
                    UnbanAction::make()->authorize(fn(User $user): bool => Gate::allows('reactivate', $user)),

                    // Default delete actions
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
