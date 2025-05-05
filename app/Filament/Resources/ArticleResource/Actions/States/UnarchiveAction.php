<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

final class UnarchiveAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return trans('Herstellen');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('unarchive', $this->record);
        $this->color('gray');
        $this->icon('heroicon-o-archive-box-x-mark');
        $this->requiresConfirmation();

        $this->modalHeading('Artikel herstellen');
        $this->modalIcon('heroicon-o-archive-box-x-mark');
        $this->modalIconColor('warning');
        $this->modalDescription('Indien u het artikel terug haalt uit het archief haalt. Zal het terug gepubliceerd worden in het Vlaams Woordenboek. Bent u zeker dat u dit wilt doen?');
        $this->modalSubmitActionLabel('Ja, ik weet het zeker');

        $this->successNotificationTitle('het artikel is terug uit het archief gehaald');
        $this->failureNotificationTitle('Helaas! Er is iets misgelopen!');

        $this->action(function (): void {
            if ($this->process(fn () => $this->record->articleStatus()->transitionToReleased())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
