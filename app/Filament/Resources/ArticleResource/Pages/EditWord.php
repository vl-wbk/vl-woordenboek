<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleStates;
use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\HasWizard;
use Kenepa\ResourceLock\Resources\Pages\Concerns\UsesResourceLock;

final class EditWord extends EditRecord
{
    use UsesResourceLock;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->state === ArticleStates::New || $this->record->state === ArticleStates::Archived) {
            $data['state'] = ArticleStates::Draft;
            $data['editor_id'] = auth()->id();
        }

        return $data;
    }
}
