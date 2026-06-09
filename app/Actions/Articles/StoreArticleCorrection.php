<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Concerns\HandlesDatabaseTransactions;
use App\Data\Article\CorrectionData;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;

/**
 * Handles the submission of an article correction by the authenticated user.
 *
 * Privileged users (those with the 'artikel beschrijvingen bewerken' permission) have their
 * correction applied immediately and are awarded points. All other users have their correction
 * queued as a propesal for moderation.
 *
 * The entire operation is wrapped in a database transaction to guarantee actomicity
 * across the update, point reward, or proposal persistance.
 *
 * @package App\Actions\Articles
 */
final readonly class StoreArticleCorrection
{
    use HandlesDatabaseTransactions;

    /**
     * Execute the action.
     *
     * @param  Article         $article         The article being corrected.
     * @param  CorrectionData  $correctionData  The submitted correction payload.
     * @return void
     *
     *
     * @throws Throwable If the transaction fails or any inner operations throw.
     */
    public function __invoke(Article $article, CorrectionData $correctionData): void
    {
        $this->executeInTransaction(fn () => $this->handle($article, $correctionData));
    }

    /**
     * Route the correction to either direct application or the moderation queue.
     * Flashes a contextual success message to the session in both cases.
     *
     * @param  Article         $article         The article being corrected.
     * @param  CorrectionData  $correctionData  The submitted payload.
     * @return void
     */
    private function handle(Article $article, CorrectionData $correctionData): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($this->shouldApplyDirectly($user, $article, $correctionData)) {
            $this->applyAndReward($article, $correctionData, $user);
            flash('Bedankt voor je bijdrage. Het artikel is bewerkt met behulp van jouw opgegeven informatie.', 'alert-success');
            return;
        }

        $this->persistProposal($article, $correctionData);
        flash('We hebben je correctie goed ontvangen! We modereren deze zo spoedig mogelijk.', 'alert-success');
    }

    /**
     * Determine whether the correction should bypass moderation and be applied immediately.
     *
     * Returns true only when the user holds the direct-edit permission AND the proposed
     * description actually differs from the current one, preventing no-op writes.
     *
     * @param  User            $user     The authenticated user submitting the correction.
     * @param  Article         $article  The article being corrected.
     * @param  CorrectionData  $data     The submitted correction payload.
     *
     * @return bool True if the correction should be applied directly, false if it should be queued.
     */
    private function shouldApplyDirectly(User $user, Article $article, CorrectionData $data): bool
    {
        return $user->canPerform('artikel beschrijvingen bewerken')
            && $this->isDescriptionDifferent($article, $data->description);
    }

    /**
     * Apply the corrected description to the article and reward the contributing user.
     *
     * Performs a secondary staleness check inside the transaction before writing,
     * guarding against a concurrent update that may have already changed the
     * description between the initial permission check and this write.
     *
     * @param  Article         $article The article to update.
     * @param  CorrectionData  $data    The submitted correction payload.
     * @param  User            $user    The user to award points to upon a successful update.
     * @return void
     */
    private function applyAndReward(Article $article, CorrectionData $data, User $user): void
    {
        if (! $this->isDescriptionDifferent($article, $data->description)) {
            return;
        }

        $article->update(['description' => $data->description]);
        $user->awardPoints(2, 'Correctie van een artikel beschrijving');
    }

    /**
     * Determine wether the proposed description differs from the article's current one.
     *
     * Both values are trimmed before comparison to avoid treating insignificant
     * leading/trailing whitespace as a meaningful change.
     *
     * @param Article   $article        The article whose current description is used as the baseline.
     * @param string    $newDescription The proposed replacement description.
     *
     * @return bool True if the description differ after trimming, false if they are identical.
     */
    public function isDescriptionDifferent(Article $article, string $newDescription): bool
    {
        return trim($article->description) !== trim($newDescription);
    }

    /**
     * Persist the correction as a pending proposal linked to the article.
     *
     * The proposal is authored by the currently authenticated user and will
     * remain in the moderation queue until reviewed.
     *
     * @param  Article          $article  The article, the proposal is linked to.
     * @param  CorrectionData   $data     The submitted correction payload.
     * @return void
     */
    private function persistProposal(Article $article, CorrectionData $data): void
    {
        $article->corrections()
            ->make($data->toArray())
            ->setCurrentUserAsAuthor()
            ->save();
    }
}
