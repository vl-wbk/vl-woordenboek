<?php

namespace App\Services;

use App\Models\Article;
use App\Models\SyllableSuggestion;
use Illuminate\Support\Facades\DB;

class SyllableModeration
{
    /** Returns true if the article now has approved syllables because of this call. */
    public static function run(Article $article, SyllableSuggestion $suggestion): bool
    {
        if (! config('syllables.auto_approve')) {
            return false;
        }

        return DB::transaction(function () use ($article, $suggestion) {
            // Lock the row so two simultaneous votes can't both publish
            $article = Article::whereKey($article->id)->lockForUpdate()->first();

            if ($article->syllables) {
                return false; // already decided
            }

            $winningSignature = self::trusted($suggestion)
                ? $suggestion->signature
                : self::consensus($article);

            if (! $winningSignature) {
                return false;
            }

            $winner = SyllableSuggestion::where('article_id', $article->id)
                ->where('status', 'pending')
                ->where('signature', $winningSignature)
                ->first();

            // forceFill so this works regardless of $fillable on Article
            $article->forceFill([
                'syllables'    => $winner->syllables,
                'stress_index' => $winner->stress_index,
            ])->save();

            SyllableSuggestion::where('article_id', $article->id)
                ->where('status', 'pending')
                ->update([
                    'status'        => DB::raw("CASE WHEN signature = " . DB::getPdo()->quote($winningSignature) . " THEN 'approved' ELSE 'rejected' END"),
                    'auto_approved' => true,
                    'decided_at'    => now(),
                ]);

            return true;
        });
    }

    private static function trusted(SyllableSuggestion $suggestion): bool
    {
        $roles = config('syllables.trusted_roles', []);

        return $roles && $suggestion->user?->hasAnyRole($roles);
    }

    /** Signature that has enough votes and a clear lead, or null. */
    private static function consensus(Article $article): ?string
    {
        $tally = SyllableSuggestion::where('article_id', $article->id)
            ->where('status', 'pending')
            ->selectRaw('signature, count(*) as votes')
            ->groupBy('signature')
            ->orderByDesc('votes')
            ->get();

        $leader = $tally->first();
        if (! $leader) {
            return null;
        }

        $runnerUp = $tally->skip(1)->first()?->votes ?? 0;

        $enoughVotes = $leader->votes >= config('syllables.min_votes', 3);
        $clearLead   = ($leader->votes - $runnerUp) >= config('syllables.min_margin', 2);

        return ($enoughVotes && $clearLead) ? $leader->signature : null;
    }
}
