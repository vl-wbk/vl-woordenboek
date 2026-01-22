<?php 

declare(strict_types=1); 

namespace App\Filament\Resources\Articles\Actions;

use App\Models\Article;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Actions\RestoreAction;

final class RestoreArticleAction extends RestoreAction
{
    use CanCustomizeProcess; 

    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (): void {
            if ($this->process(fn (Article $article): bool => $this->handleArticleRestoration($article))) {
                $this->success(); 
                return;
            }

            $this->failure();
        });
    }

    private function handleArticleRestoration(Article $article): bool 
    {
        $article->update(attributes: ['deletion_reason' => null, 'deleted_by' => null]);

        return $article->restore();
    } 
}