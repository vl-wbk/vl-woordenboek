<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;


use Filament\Actions\Action;

/**
 * @property \App\Models\Article $record
 */
final class AcceptPublishingProposal extends Action
{
    public static function getDefaultName(): string
    {
        return trans('artikel publiceren');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('success');
        $this->icon('heroicon-o-check');
        $this->authorize('publish-article', $this->record);

        $this->action(function (): void {
            $this->record->articleStatus()->transitionToReleased();
            $this->success();
        });
    }
}
