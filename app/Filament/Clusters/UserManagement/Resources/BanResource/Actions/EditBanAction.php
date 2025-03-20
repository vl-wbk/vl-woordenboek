<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\BanResource\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

/**
 * Welcome to the Edit Ban Action - a key part of our moderation system.
 *
 * This action handles the editing of account deactivations through a modal interface.
 * We've designed it to be straightforward: moderators can update both the reason and duration of a deactivation in one place.
 * You'll notice we use Dutch throughout the interface since that's our community's language, and we prefer the term "deactivation" over "ban" to keep things professional.
 *
 * The interface includes confirmation steps to prevent accidental changes, and we make sure to show clear feedback messages in case something goes wrong.
 * The whole system integrates seamlessly with our permission system through the UserPolicy.
 *
 * Feel free to extend this action - common improvements might include adding new fields, customizing the update process, or enhancing the validation rules.
 *
 * @see \App\Policies\UserPolicy  For authorization rules regaring the action
 * @see https://github.com/Gerenuk-LTD/filament-banhammer/blob/main/src/Resources/Actions/EditBanAction.php
 *
 * @package App\Filament\Clusters\UserManagement\Resources\BanResource\Actions
 */
final class EditBanAction extends Action
{
    use CanCustomizeProcess;

    /**
     * This method provides the internal identifier for our action.
     * It's used by Filament's event system and general action handling.
     * While you probaly won't need to change this, it's good to know it's here if you need to reference this action elsewhere in the code.
     *
     * @return string
     */
    public static function getDefaultName(): string
    {
        return 'edit_ban';
    }

    /**
     * The setUp method is where we configure everything about our action's behavior.
     * We set the visual elements like labels and icons, configure the form, and define what happens when someone submits changes.
     *
     * The action uses a warning color scheme with a pencil icon to indicate its editing purpose.
     * When triggered, it shows a modal with pre-filled form fields and requires confirmation before saving any changes.
     *
     * The form submission process updates the ban record and shows appropriate feedback messages in Dutch.
     * We've made sure to handle both success and failure cases gracefully.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Wijzigen');
        $this->color('warning');
        $this->icon('heroicon-o-pencil-square');
        $this->modalHeading('Deactivatie wijzigen');
        $this->modalSubmitActionLabel('Bevestigen');
        $this->requiresConfirmation();

        $this->form($this->formSchema());
        $this->fillForm(fn (Model $record): array => $record->attributesToArray());

        $this->action(function (): void {
            $result = $this->process(static fn (array $data, Model $record) => $record->update([
                'comment' => $data['comment'],
                'expired_at' => $data['expired_at'],
            ]));

            $this->successNotificationTitle('De deactivatie gegevens zijn aangepast');
            $this->failureNotificationTitle('Oops! er is iets misgelopen!');

            if (! $result) {
                $this->failure();

                return;
            }

            $this->success();
        });
    }

    /**
     * Here we define the structure of our editing form.
     * The form currently includes two main components: a text area for the deactivation reason and a date/time picker for setting when the deactivation expires.
     * Both fields use Dutch labels to match our interface language.
     *
     * The comment field is optional, allowing moderators to leave it empty if needed.
     * The expiration date, however, is required to ensure every deactivation has a defined duration.
     *
     * If you need to add more fields, this is the perfect place. Just remember to update the action handling in setUp() to process any new fields you add.
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
