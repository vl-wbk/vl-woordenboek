<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Article;
use Illuminate\Support\Facades\Storage;

/**
 * Handle model lifecycle events for the Article model.
 *
 * This observer orchestrates the cascading cleanup, asset disposal, and restoration workflows associated with Article records.
 * It explicitly handles soft deletes vs. force deletes for  dependent relational data and manages physical storage cleanups when media assets (like charts)
 * are updated or permanently expunged.
 *
 * When adding new file upload attributes or dependent relationships to the Article model, ensure their respective decoupling or deletion
 * hooks are extended here to prevent orphan files in storage or stale/integrity issues in the database layers.
 *
 * @package App\Observers
 */
final readonly class ArticleObserver
{
    /**
     * Handle the Article "deleting" event.
     *
     * Synchronizes deletion sctops to child 'userExamples' records based on whether the operation is a temporary soft delete or a permanent erasure.
     * Additionally, if the record is being permanently purged, it sensures that any associated physical asset files store on the
     * public disk are unlinked to avoid storage leaks.
     *
     * @param  Article $article The specific article instance undergoing the deletion process.
     * @return void
     */
    public function deleting(Article $article): void
    {
        if ($article->isForceDeleting()) {
            $article->userExamples()->forceDelete();
        } else {
            $article->userExamples()->delete();
        }

        if ($article->isForceDeleting()) {
            if ($article->region_chart && Storage::disk('public')->exists($article->region_chart)) {
                Storage::disk('public')->delete($article->region_chart);
            }
        }
    }

    /**
     * Handle the Article "Updated" event.
     *
     * Monitors updates specifically targetting the 'region_chart' media file reference attribute.
     * If a new asset replaces an older reference, this hook intercepts the transaction to locate and permanentlu purge
     * the obsolete physical file from the underlying storage disk.
     *
     * @param  Article $record The specifix article instance containing the modified state modifications.
     * @return void
     */
    public function updated(Article $record): void
    {
        if ($record->wasChanged('region_chart')) {
            $oldImage = $record->getOriginal('region_chart');

            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }
    }

    /**
     * Handle the Article "restoring" event.
     *
     * Reverses the soft-delete cascade sequence when an 'un-delete' command is executed on the main article record.
     * This ensures that any child 'userExamples' that were soft-deletedduring the parent's archiving step are
     * brought back online concurrently.
     *
     * @param  Article $article The archived article instance undergoing recovery.
     * @return void
     */
    public function restoring(Article $article): void
    {
        $article->userExamples()->restore();
    }
}
