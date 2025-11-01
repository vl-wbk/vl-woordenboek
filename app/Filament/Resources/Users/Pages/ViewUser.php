<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * ViewUser page class for displaying individual user records.
 *
 * This class represents the view page in the Filament user resource, providing a read-only display of a single user's
 * information. It extends Filament's ViewRecord page and customizes the header actions to allow quick transitions to
 * edit or deleting the currently viewed user.
 *
 * The page displays all user information in an infolist format as configured in UserResource::infolist(), including
 * personal details, account status, verification state, and relevant timestamps.
 *
 * Access to this page is controlled through Filament's authorization system, respecting the user policies for the
 * User Model.
 *
 * @see UserResource::infolist() - For the structure of displayed information
 * @see UserResource::class - For the parent resource configuration
 */
final class ViewUser extends ViewRecord
{
    /**
     * The resource class this page belongs to.
     *
     * Links this view page to the UserResource, ensuring proper routing,
     * authorization, and data handling for user records.
     *
     * @var string
     */
    protected static string $resource = UserResource::class;

    /**
     * Defines the action buttons displayed in the page header.
     *
     * Provides quick access to common operations on the currently viewed user:
     *
     * - Edit: Opens the user edit form, styled with gray color and a pencil icon
     *   for a subtle, professional appearance that indicates modification capability
     *
     * - Delete: Allows removal of the user record, marked with a trash icon
     *   for clear visual communication of the destructive action
     *
     * Both actions respect the user's permissions and policy gates. If the current
     * administrator lacks permission to edit or delete, those actions will be
     * automatically hidden by Filament's authorization layer.
     *
     * @return array<Action> Array of action instances for the page header
     */
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->color('gray')
                ->icon('heroicon-o-pencil-square'),

            DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
