<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\BanResource\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

/**
 * @see https://github.com/Gerenuk-LTD/filament-banhammer/blob/main/src/Resources/Actions/EditBanAction.php
 */
final class EditBanAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'edit_ban';
    }

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
