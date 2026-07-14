<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Actions;

use App\Enums\Articles\ArticleDeletionReasons;
use App\Models\Article;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

final class SoftDeleteArticleAction extends DeleteAction
{
    use CanCustomizeProcess;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema(schema: $this->registerCustomModalSchema());

        $this->action(function (): void {
            if ($this->process(fn (Article $article, array $data): bool => $this->softDeleteArticle($article, $data))) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }

    /**
     * @param  Article $article
     * @param  array{motivation: string, deletion_reason: string} $data
     * @return bool
     */
    private function softDeleteArticle(Article $article, array $data): bool
    {
        $article->update(attributes: [
            'deletion_reason' => $data['deletion_reason'],
            'deleted_by' => auth()->user()->id
        ]);

        return (bool) $article->delete();
    }

    /**
     * @return array<Select|Textarea>
     */
    private function registerCustomModalSchema(): array
    {
        return [
            Select::make('motivation')
                ->label('Redenen')
                ->hiddenLabel()
                ->placeholder('selecteer een redenen')
                ->options(ArticleDeletionReasons::class)
                ->native(false)
                ->afterStateUpdated(fn (Set $set, ?ArticleDeletionReasons $state): mixed => $set('deletion_reason', $state?->getLabel()))
                ->live(),

            Textarea::make('deletion_reason')
                ->label('Reden tot verwijdering')
                ->rows(3)
                ->required()
                ->placeholder('Beschrijf kort waarom het artikel verwijderd moet worden.')
                ->characterLimit(400)
                ->default(null)
        ];
    }
}
