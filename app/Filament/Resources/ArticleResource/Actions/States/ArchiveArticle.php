<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;

use Filament\Actions\Action;

final class ArchiveArticle extends Action
{
    protected string $actionIcon = 'heroicon-o-archive-box';

    public static function getDefaultName(): ?string
    {
        return trans('Artikel archiveren');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('gray');
        $this->icon($this->actionIcon);
        $this->authorize('archive-article', $this->record);

        // Confirmation box configuration
        $this->requiresConfirmation();
        $this->modalIcon($this->actionIcon);
        $this->modalHeading('Artikel archiveren');
        $this->modalDescription('Indien u het artikel in het archief stopt. Zal deze echter een beperkte zichtbaarheid hebben. En niet raadplaagbaar zijn voor eind gebruikers');

        $this->action(function (): void {
            $this->record->articleStatus()->transitionToArchived();
            $this->success();
        });
    }
}
