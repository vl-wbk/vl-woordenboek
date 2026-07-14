<?php

declare(strict_types= 1);

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Users\Pages\ViewUser;
use App\Models\User;
use App\Services\PasskeyAuthenticatorAaguids;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class PasskeysRelationManager extends RelationManager
{
    protected static string $relationship = 'passkeys';

    protected static ?string $title = 'Passkeys';

    protected static string|BackedEnum|null $icon = Heroicon::OutlinedKey;

    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * @param  User   $ownerRecord
     * @param  string $pageClass
     * @return bool
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        return $pageClass === ViewUser::class
            && $ownerRecord->passKeys()->count() > 0
            && $authUser->isDeveloper();
    }

    /**
     * @param  User $ownerRecord
     * @param  string $pageClass
     * @return string
     */
    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) $ownerRecord->passkeys()->count();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Passkeys')
            ->description('Beheer de passkeys die gekoppeld zijn aan dit account voor veilige, wachtwoordloze authenticatie.')
            ->columns($this->registerTableSchemaLayout())
            ->recordActions($this->registerRevokeAction());
    }

    /**
     * @return array<IconColumn|TextColumn>
     */
    private function registerTableSchemaLayout(): array
    {
        return [
            TextColumn::make('name')
                ->label('Sleutel')
                ->icon(Heroicon::OutlinedKey)
                ->iconColor('primary')
                ->color('primary')
                ->weight(FontWeight::Bold)
                ->sortable()
                ->searchable(),

            TextColumn::make('data.aaguid')
                ->label('Provider')
                ->formatStateUsing(function (string $state): string {
                    return PasskeyAuthenticatorAaguids::findByAaguid($state)['name'] ?? 'onbekende provider';
                }),

            IconColumn::make('data.uvInitialized')
                    ->label('Geverifieerd')
                    ->boolean()
                    ->tooltip('Is de gebruiker lokaal geverifieerd via biometrie of PIN?'),

            IconColumn::make('data.backupStatus')
                    ->label('Gesynchroniseerd')
                    ->boolean()
                    ->trueIcon('heroicon-o-cloud-arrow-up')
                    ->falseIcon('heroicon-o-computer-desktop')
                    ->tooltip('Staat deze sleutel in de cloud of enkel op dit apparaat?'),

            TextColumn::make('last_used_at')
                ->label('Laast gebruikt')
                ->placeholder('-')
                ->since(),


            TextColumn::make('created_at')
                ->label('Registratie datum')
                ->date()
                ->sortable(),
        ];
    }

    /**
     * @return DeleteAction[]
     */
    private function registerRevokeAction(): array
    {
        return [
            DeleteAction::make()
        ];
    }
}
