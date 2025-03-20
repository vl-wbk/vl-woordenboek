<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Actions;

use App\Models\User;
use Cog\Laravel\Ban\Models\Ban;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

/**
 * @see https://github.com/Gerenuk-LTD/filament-banhammer/blob/main/src/Resources/Actions/UnbanAction.php
 */
final class UnbanAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): string
    {
        return trans('reactiveer');
    }

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
