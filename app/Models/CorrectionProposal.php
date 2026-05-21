<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Relations\BelongsToAuthor;
use App\States\Articles\Corrections\CorrectionState;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use Spatie\ModelStates\HasStates;

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
     * @throws \Throwable
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
