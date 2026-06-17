<?php

namespace App\Actions\Reputation;

use App\Models\Appeal;
use App\Models\ReputationLog;
use App\Notifications\AppealReviewedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppealReviewAction
{
    public function execute(Appeal $appeal, string $status, ?string $moderatorNote = null): void
    {
        // Guard: only process pending appeals
        if ($appeal->status !== 'pending') {
            throw new \LogicException("Beroep #{$appeal->id} is al beoordeeld.");
        }

        DB::transaction(function () use ($appeal, $status, $moderatorNote) {
            $appeal->update([
                'status'         => $status,
                'moderator_note' => $moderatorNote,
                'reviewed_at'    => now(),
                'reviewed_by'    => auth()->id(),
            ]);

            if ($status === 'approved') {
                $this->reverseReputationChange($appeal);
            }

            // $this->notifyUser($appeal);
        });
    }

    private function reverseReputationChange(Appeal $appeal): void
    {
        $log = $appeal->reputationLog;

        if (!$log) {
            Log::warning("AppealReviewAction: reputationLog not found for appeal #{$appeal->id}");
            return;
        }

        // Reverse the original points
        $appeal->user->decrement('reputation', $log->points);

        // Clamp reputation to 0 — never go negative
        if ($appeal->user->reputation < 0) {
            $appeal->user->update(['reputation' => 0]);
        }

        // Write a new log entry for the reversal
        ReputationLog::create([
            'user_id' => $appeal->user_id,
            'points'  => -$log->points,
            'type'    => 'award',
            'reason'  => "Beroep toegekend: {$log->reason}",
        ]);
    }

    private function notifyUser(Appeal $appeal): void
    {
        try {
            $appeal->user->notify(new AppealReviewedNotification($appeal));
        } catch (\Throwable $e) {
            // Don't let a notification failure roll back the transaction
            Log::error("AppealReviewAction: failed to notify user #{$appeal->user_id} — {$e->getMessage()}");
        }
    }
}
