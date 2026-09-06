<?php

namespace App\Domain\Voting;

use App\Jobs\RecalculateArticleQuality;
use App\Models\Article;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class VoteService
{
    public function vote(
    User $user,
    Article $article,
    int $value,
): Vote {
    if (! in_array($value, [1, -1], true)) {
        throw new InvalidArgumentException(
            'Vote must be 1 or -1.'
        );
    }

    return DB::transaction(function () use (
        $user,
        $article,
        $value,
    ) {
        $vote = Vote::query()
            ->where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->first();

        $changed = $vote === null || $vote->value !== $value;

        if ($vote === null) {
            $vote = Vote::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
                'value' => $value,
            ]);
        } elseif ($changed) {
            $vote->update([
                'value' => $value,
            ]);
        }

        if ($changed) {
            $article->increment('votes_version');

            RecalculateArticleQuality::dispatch($article->id)
                ->afterCommit();
        }

        return $vote;
    });
}

    public function remove(
    User $user,
    Article $article,
): void {
    DB::transaction(function () use ($user, $article) {
        $deleted = Vote::query()
            ->where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->delete();

        if ($deleted === 0) {
            return;
        }

        $article->increment('votes_version');

        RecalculateArticleQuality::dispatch($article->id)
            ->afterCommit();
    });
}
}
