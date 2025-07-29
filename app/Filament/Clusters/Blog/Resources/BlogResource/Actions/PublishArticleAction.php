<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * @toàdo document this class
 * @property \App\Models\Blog $record The database entity from the blog post in the database
 */
final class PublishArticleAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): string
    {
        return 'publish-article';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Publiceren');
        $this->icon('tabler-eye-check');
        $this->color('success');

        $this->visible($this->record->status->isDraft());
        $this->authorize('publish', $this->record);

        $this->requiresConfirmation();

        $this->modalHeading('Nieuwsartikel publiceren');
        $this->modalDescription('U staat op het punt om een nieuwsartikel te publiceren. Weet u zeker dat u deze handeling wilt uitvoeren?');
        $this->modalIcon('tabler-eye-check');
        $this->modalSubmitActionLabel('Ja, ik ben zeker');

        $this->successNotificationTitle('Het nieuwsartikel is gepubliceerd');
        $this->failureNotificationTitle('Helaas pindaklaas! Er is iets misgelopen');

        $this->action(function (): void {
            if ($this->process(fn(): bool => $this->record->publicationStatus()->transitionToPublished())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
