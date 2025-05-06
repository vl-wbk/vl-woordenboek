<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

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
        $this->modalHeading('Publicatie ongedaan maken');
        $this->modalDescription('Bij het ongemaken van een publicatie zal het artikel niet meer raadpleegbaar zijn voor gebruikers. Dit kan handig zijn voor een herwerking van het artikel.');

    }
}
