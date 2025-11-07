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
use Throwable;

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

    /**
     * @throws Throwable
     */
    private function duplicateArticle(Article $article): bool
    {
        return DB::transaction(function () use ($article) {
            $regions = $article->regions()->pluck('regions.id')->toArray();

            $newArticle = $article->replicate($this->excludedFields());
            $newArticle->fill($this->getResetFieldsForDuplicate($article));

            if ($newArticle->save()) {
                $newArticle->regions()->sync($regions);

                foreach ($article->sources as $source) {
                    $newArticle->sources()->save($source);
                }

                $this->newArticleInstance = $newArticle; // Store the article
                return true;

            }

            return false;
        });
    }

    /**
     * Defines the fields to be reset of modified when duplicating an article.
     *
     * This method returns an array of field values that will be applied to the duplicated article.
     * It handles three main categories of field modifications:
     *
     * 1. Title modification: Appends '- Duplicatie' to the original word/title
     * 2. State resets: Clears publication, archiving, and contributor metadata
     * 3. Counter restes: Resets view counts, votes and feature flags
     * 4. Authorship: Assigns the current authenticated user as author and editor
     *
     * The duplicated article is always created in Draft state, ensuring it requires review befire publication.
     *
     * @param  Article $originalArticle  The article being duplicated
     * @return array<string, mixed>      Associative array of field names and their new values
     */
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

    /**
     * Returns an array of field names to exclude from the duplication process.
     *
     * These fields should not be copied from the original article to the duplicate.
     *
     * Currently, excludes:
     * - audits_count: Prevents copying the audit count, as the new article should start fresh.
     *
     * @return list<string> Array of field names to exclude during duplication.
     */
    private function excludedFields(): array
    {
        return ['audits_count'];
    }
}
