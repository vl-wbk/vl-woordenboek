<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;

use Filament\Actions\Action;

final class PublishArticleAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'insturen voor publicatie';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-paper-airplane');
        $this->color('gray');

        // Configuration on the conformation model
        $this->requiresConfirmation();
        $this->modalHeading(fn (): string => trans('Artikel insturen voor publicatie.'));
        $this->modalDescription('Nadat u het artikel instuurt voor nazicht zal hij/zij het artikel nakijken en mogelijks goedkeuren voor publictatie');
        $this->modalSubmitActionLabel('Insturen');
        $this->authorize('sendForApproval', $this->record);
        $this->modalIcon('heroicon-o-paper-airplane');

        $this->action(function (): void {
            $this->record->articleStatus()->transitionToApproved();
            $this->success();
        });
    }
}
