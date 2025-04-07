<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListUsers extends ListRecords
{
    /**
     * Specifies the resource class this listing bpage belongs to.
     * This association embers proper routing an resource management within the filament aadmin panel structure.
     */
    protected static string $resource = UserResource::class;

    /**
     * Configuring the actions available in the page header.
     *
     * Provides a user creation action witrh Dutch language labels and appropriate inconography.
     * Thus user-plus icon visually reinforces the action's purpose while maintaining consistency with the application's visual language.
     *
     * @return array<int, Actions\CreateAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Gebruiker toevoegen')
                ->icon('heroicon-o-user-plus'),
        ];
    }
}
