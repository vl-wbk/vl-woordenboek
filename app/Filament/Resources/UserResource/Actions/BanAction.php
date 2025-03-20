<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Actions;

use App\Models\User;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DateTimePicker;

/**
 * This action handles the process of deactivating user accounts in our Flemish dictionary community.
 * It creates a modal interface where moderators can specify both a reason for the deactivation and when it should expire.
 *
 * You'll notice we use the term "deactivate" instead of "ban" in our Dutch interface to maintain a professional tone.
 * The action appears as a red shield-lock button in the user management table.
 *
 * The form includes safety features like confirmation dialogs and clear feedback messages.
 * Everything is in Dutch to match our community's language preferences.
 *
 * @see \App\Policies\UserPolicy For permission handling
 * @see \Cog\Laravel\Ban\Traits\Bannable For the ban implementation
 * @see https://github.com/Gerenuk-LTD/filament-banhammer/blob/main/src/Resources/Actions/BanAction.php
 *
 * @package App\Filament\Resources\UserResource\Actions
 */
final class BanAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Provides the translation key for our action's name.
     * We use a simple Dutch string here since our entire interface is in Dutch.
     * This appears in various places throughout the admin panel.
     */
    public static function getDefaultName(): string
    {
        return trans('Deactiveer');
    }

    /**
     * This is where we configure how our action looks and behaves.
     * The method sets up the visual elements, form structure, and handles what happends when a moderator submits the deactivation form.
     *
     * We use a danger color scheme and shield-lock icon to indicate the serious nature of this action.
     * The form requires explicit confirmation before proceeding with the deactivation.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Deactiveer');
        $this->color('danger');
        $this->icon('tabler-shield-lock');
        $this->modalHeading('Gebruiker deactiveren');
        $this->modalSubmitActionLabel('Bevestigen');
        $this->requiresConfirmation();

        $this->form(
            $this->formSchema()
        );

        $this->action(function (): void {
            /**
             * @todo Make use of an Dataobject in this data storage handling.
             */
            $result = $this->process(static fn (array $data, User $record) => $record->ban([
                'comment' => $data['comment'],
                'expired_at' => $data['expired_at'],
            ]));

            $this->failureNotificationTitle('Oops! Er is iets fout gelopen.');
            $this->successNotificationTitle('Het gebruikersaccount is gedeactiveerd');

            if (! $result) {
                $this->failure();
                return;
            }

            $this->success();
        });
    }

    /**
     * Defines our deactivation form structure.
     * The form includes two main fields: a text area for explaining the deactivation reason and a date/time picker for setting when the deactivation should expire.
     *
     * The reason field is optional, but an expiration date is required to ensure every deactivation has a defined duration.
     * All labels are in Dutch to maintain interface consistency.
     *
     * @return array<int, DateTimePicker|Textarea> The form field configuration
     */
    public function formSchema(): array
    {
        return [
            Textarea::make('comment')
                ->label('Reden tot deactivering')
                ->nullable(),
            DateTimePicker::make('expired_at')
                ->required()
                ->label('Verloopt op'),
        ];
    }
}
