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

        if ($article->user_id === $user->id) {
            throw new InvalidArgumentException(
                'You cannot vote on your own article.'
            );
        }

        $vote = DB::transaction(function () use (
            $user,
            $article,
            $value,
        ) {
            return Vote::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'article_id' => $article->id,
                ],
                [
                    'value' => $value,
                ],
            );
        });

        RecalculateArticleQuality::dispatch(
            $article->id
        )->afterCommit();

        return $vote;
    }

    public function remove(
        User $user,
        Article $article,
    ): void {
        Vote::query()
            ->where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->delete();

        RecalculateArticleQuality::dispatch(
            $article->id
        )->afterCommit();
    }
}
