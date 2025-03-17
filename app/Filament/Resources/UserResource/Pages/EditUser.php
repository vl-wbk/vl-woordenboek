<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * EditUser provides the user editing interface in the Vlaams Woordenboek admin panel.
 *
 * This page component handles the modification of existing user records through a form-based interface.
 * It extends Filament's EditRecord to provide a standardized editing experience while maintaining the application's design patterns and Dutch-language interface elements.
 *
 * @package App\Filament\Resources\UserResource\Pages
 */
final class EditUser extends EditRecord
{
    /**
     * Associates this page with the UserResource, enabling proper routing and form generation within the Filament admin panel.
     * This connection ensures that all user-related operations maintain consistency throughout the application.
     */
    protected static string $resource = UserResource::class;

    /**
     * Configures the actions available in the page header during user editing.
     * Currently provides a delete action for removing users from the system when necessary.
     * The action includes proper confirmation dialogs to prevent accidental user deletion.
     *
     * @return array<int, Actions\DeleteAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
