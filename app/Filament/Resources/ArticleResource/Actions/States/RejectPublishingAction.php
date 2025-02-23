<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;

use Filament\Actions\Action;

final class RejectPublishingAction extends Action
{
    protected string $actionIcon = 'heroicon-o-x-mark';

    public static function getDefaultName(): ?string
    {
        return trans('Publicatie afwijzen');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('danger');
        $this->icon($this->actionIcon);
        $this->authorize('reject-publication', $this->record);

        // Confirmation config
        $this->requiresConfirmation();
        $this->modalIcon($this->actionIcon);
        $this->modalHeading('Voorstel tot publicatie afwijzen');
        $this->modalDescription('Indien het voorstel tot publicatie afwijst zal het artikel terug geplaatst worden in de lijst van te bewerken artikelen.');
        $this->modalSubmitActionLabel('Ja, ik weet het zeker');

        $this->action(function (): void {
            $this->record->articleStatus()->transitionToEditing();
            $this->success();
        });
    }
}
