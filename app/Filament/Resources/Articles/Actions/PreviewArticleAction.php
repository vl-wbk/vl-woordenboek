<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Actions;

use App\Models\Article;
use App\Policies\ArticlePolicy;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

final class PreviewArticleAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'preview-article';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Preview');
        $this->color('gray');
        $this->authorize(ArticlePolicy::DisplayArticle);
        $this->icon(Heroicon::OutlinedEye);
        $this->hidden(fn (Article $article): bool => $article->trashed());
        $this->url(fn (Article $article): string => route('word-information.show', $article), shouldOpenInNewTab: true);
    }
}
