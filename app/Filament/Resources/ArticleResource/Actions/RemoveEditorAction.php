<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Enums\MaxWidth;

/**
 * @property \App\Models\Article $record The dictionary article being submitted for publication
 */
final class RemoveEditorAction extends Action
{
    use CanCustomizeProcess;

    protected static string $navigationIcon = 'tabler-link-minus';

    public static function getDefaultName(): string
    {
        return 'remove-editor';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Loskoppelen');
        $this->icon(self::$navigationIcon);
        $this->color('danger');

        $this->requiresConfirmation();
        $this->modalWidth(MaxWidth::Large);
        $this->modalCloseButton(false);
        $this->modalIcon(self::$navigationIcon);
        $this->modalHeading($this->getCustomUserBasedModalHeading());
        $this->modalDescription($this->getCustomUserBasedModalDescription());
        $this->modalSubmitActionLabel('Ja, ik ben zeker');

        $this->action(function (): void {
            if ($this->process(fn (): bool => $this->record->articleStatus()->transitionToSuggestion())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }

    private function getCustomUserBasedModalHeading(): string
    {
        $heading = ($this->isAuthenticatedUser())
            ? 'Redacteurschap beëindigen'
            : 'Redacteur loskoppelen';

        return trans($heading);
    }

    private function getCustomUserBasedModalDescription(): string
    {
        $description = ($this->isAuthenticatedUser())
            ? 'Weet je zeker dat je jezelf wilt loskoppelen van dit artikel? Je verliest toegang en het artikel wordt opnieuw gemarkeerd als suggestie, ook als je al bewerkingen hebt gedaan. Deze actie kan niet ongedaan worden.'
            : 'Weet je zeker dat je deze redacteur wilt loskoppelen? Dit kan niet ongedaan worden gemaakt. Het artikel wordt teruggezet als suggestie en de redacteur verliest toegang.';

        return trans($description);
    }

    private function isAuthenticatedUser(): bool
    {
        return $this->record->editor()->is(auth()->user());
    }
}
