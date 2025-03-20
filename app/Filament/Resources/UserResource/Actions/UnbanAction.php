<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Actions;

use App\Models\User;
use Cog\Laravel\Ban\Models\Ban;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

/**
 * This action handles the reactivation of previously deactivated user accounts in our Flemish dictionary community.
 * It provides moderators with a simple, confirmation-based interface to restore user access to the platform.
 *
 * The action is flexible - it can work with both User models and Ban records, automatically determining the correct unban approach for each case.
 * You'll notice we use "reactivation" terminology in Dutch to maintain our professional tone throughout the interface.
 *
 * The interface shows a warning-colored unlock icon to indicate its purpose, and requires explicit confirmation before proceeding with the reactivation to prevent accidental clicks.
 *
 * @see \App\Models\User For the user model implementation
 * @see \Cog\Laravel\Ban\Models\Ban For the ban record structure
 * @see https://github.com/Gerenuk-LTD/filament-banhammer/blob/main/src/Resources/Actions/UnbanAction.php
 *
 * @package App\Filament\Resources\UserResource\Actions
 */
final class UnbanAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Provides the Dutch translation key for our action's name.
     * This appears in various places throughout the admin panel to maintain language consistency.
     *
     * @var string
     */
    public static function getDefaultName(): string
    {
        return trans('reactiveer');
    }

    /**
     * Configures the action's appearance and behavior.
     * This method sets up all visual elements and defines what happens when a moderator confirms the reactivation.
     *
     * The action uses a warning color scheme with an unlock icon to indicate its purpose.
     * When triggered, it shows a confirmation dialog in Dutch before proceeding with the account reactivation.
     *
     * @var void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Reactivatie');
        $this->color('warning');
        $this->icon('tabler-lock-open-2');
        $this->modalHeading('Gebruiker heractiveren');
        $this->modalSubmitActionLabel('Bevestigen');
        $this->requiresConfirmation();

        $this->action(function (): void {
            $this->process(static function (User|Ban $record): void {
                match (true) {
                    $record instanceof User => $record->unban(),
                    $record instanceof Ban => $record->bannable->unban(),
                };
            });

            $this->successNotificationTitle('De gebruiker is terug geactiveerd in het platform');
            $this->success();
        });
    }
}
