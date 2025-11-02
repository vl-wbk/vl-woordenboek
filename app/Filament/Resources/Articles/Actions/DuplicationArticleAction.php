<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Actions;

use App\Enums\ArticleStates;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

final class DuplicationArticleAction extends Action
{
    use CanCustomizeProcess;

    private ?Article $newArticleInstance = null;

    public static function getDefaultName(): ?string
    {
        return 'duplication-action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon(Heroicon::OutlinedDocumentDuplicate);
        $this->color('gray');
        $this->label('Dupliceren');

        $this->requiresConfirmation();
        $this->modalIcon(Heroicon::OutlinedDocumentDuplicate);
        $this->modalIconColor('primary');
        $this->modalHeading('Artikel dupliceren');
        $this->modalDescription('U kunt makkelijk een artikel dupliceren als u het bestaande artikel wilt gebruiken als basis voor een nieuw artikel. Bent u zeker dat u dit wilt doen?');
        $this->modalCloseButton(false);

        $this->successNotificationTitle('Het artikel is met succes gedupliceerd');

        $this->action(function (): void {
            if ($this->process(fn(Article $article): bool => $this->duplicateArticle($article)) && $this->newArticleInstance !== null) {
                $url = ArticleResource::getUrl('edit', ['record' => $this->newArticleInstance]);
                $this->success();
                $this->redirect($url);
                return;
            }

            $this->failure();
        });
    }

    private function duplicateArticle(Article $article): bool
    {
        return DB::transaction(function () use ($article) {
            $newArticle = $article->replicate($this->excludedFields());
            $newArticle->fill($this->getResetFieldsForDuplicate($article));

            if ($newArticle->save()) {
                $this->newArticleInstance = $newArticle; // Store the article
                return true;
            }

            return false;
        });
    }

    private function getResetFieldsForDuplicate(Article $originalArticle): array
    {
        $currentUserId = auth()->id();

        return [
            // Append ' - duplicatie' to the word/title
            'word' => "{$originalArticle->word} - duplicatie",

            // Reset state-related fields
            'contributor_name' => null,
            'prune_reminder_sent_at' => null,
            'published_at' => null,
            'archived_at' => null,
            'disclaimer_id' => null,
            'archiving_reason' => null,
            'published_id' => null,
            'archiever_id' => null,
            'state' => ArticleStates::Draft,
            'notify_author' => false,

            // Reset counters/flags
            'wotd' => false, // Word of the day
            'votes_today' => 0,
            'views' => 0,

            // Set authorship to the current user
            'author_id' => $currentUserId,
            'editor_id' => $currentUserId,
        ];
    }

    private function excludedFields(): array
    {
        return ['audits_count'];
    }
}
