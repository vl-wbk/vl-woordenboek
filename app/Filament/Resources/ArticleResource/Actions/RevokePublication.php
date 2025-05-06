<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;

/**
 * @property \App\Models\Article $record The dictionary article being submitted for publication
 */
final class RevokePublication extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return trans('ongedaan maken');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('unpublish', $this->record);

        $this->icon('tabler-arrow-back-up');
        $this->color('danger');

        $this->requiresConfirmation();

        $this->modalIcon('tabler-arrow-back-up');
        $this->modalCloseButton(false);
        $this->modalHeading('Publicatie ongedaan maken');
        $this->modalDescription('Bij het ongemaken van een publicatie zal het artikel niet meer raadpleegbaar zijn voor gebruikers. Dit kan handig zijn voor een herwerking van het artikel.');
        $this->modalSubmitActionLabel('Ongedaan maken');

        $this->form([
            Textarea::make('reason')
                ->label('Reden van de handeling')
                ->placeholder('Beschrijf kort waarom je de publicatie ongedaan wilt maken.')
                ->rows(5)
                ->required()
        ]);

        $this->successNotificationTitle('Publicatie ongedaan gemaakt');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets migelopen.');

        $this->action(function (): void {
            if ($this->process(fn (array $data): bool => $this->record->articleStatus()->transitionToEditing($data['reason']))) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
