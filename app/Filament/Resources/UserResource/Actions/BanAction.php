<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DateTimePicker;

/**
 * @see https://github.com/Gerenuk-LTD/filament-banhammer/blob/main/src/Resources/Actions/BanAction.php
 */
final class BanAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return trans('Deactiveer');
    }

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
            $result = $this->process(static fn (array $data, Model $record) => $record->ban([
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
