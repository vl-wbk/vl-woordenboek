<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Relations\BelongsToAuthor;
use App\States\Articles\Corrections\CorrectionState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use Spatie\ModelStates\HasStates;
use Throwable;

/**
 * @property int             $id            The unique identifier from the correction purposal.
 * @property CorrectionState $state         The current state of the correction purposal in the system.
 * @property int             $article_id    The unique identifier from the article where the correction is related to.
 * @property int|null        $author_id     The unique identifier from the user who registered the proposal in the system.
 * @property int|null        $moderator_id  The unique identifier from the user who moderated the correction proposal.
 * @property string          $description   The suggested correction for the dictionary article.
 * @property string|null     $conclusion    The descriptive final conclusion for the correction
 * @property Carbon|null     $moderated_at  The timetamp that indicates when the proposal is moderated.
 * @property Carbon|null     $created_at    The timestamp indicating when the proposal is registered in the system.
 * @property Carbon|null     $updated_at    The timestamp that indicated when the proposal is modified for the last time.
 */
#[Fillable('description', 'reason', 'state')]
final class CorrectionProposal extends Model
{
    use BelongsToAuthor;
    use HasStates;

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @throws Throwable
     */
    public function reject(User $moderator, string $reason): void
    {
        $this->moderator()->associate($moderator);
        $this->moderated_at = now();
        $this->conclusion = $reason;

        $this->saveOrFail();
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'state' => CorrectionState::class
        ];
    }
}
