<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * @todo GH-#267
 * @property \App\Models\Blog $record  The database entity from the blog post.
 */
final class UndoPublicationAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): string
    {
        return 'undo-publication-article';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Offline halen');
        $this->icon('tabler-eye-cancel');
        $this->color('danger');

        $this->visible($this->record->status->isPublished());
        $this->authorize('undo-publication', $this->record);

        $this->requiresConfirmation();

        $this->modalHeading('Nieuwsartikel offline halen');
        $this->modalDescription('U staat op het punt om een nieuwsartikel offline te halen. Ij het offline halen zal het nog wel zichtbaar zijn in de beheersconsole. Maar niet meer voor het brede publiek. Bent u zeker dat u de actie wilt uitvoeren?');
        $this->modalIcon('tabler-eye-cancel');
        $this->modalSubmitActionLabel('Ja, ik ben zeker');

        $this->successNotificationTitle('Het nieuwsartikel is met success offline gehaald');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen');

        $this->action(function (): void {
            if ($this->process(fn (): bool => $this->record->publicationStatus()->transitionToDraft())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
