<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Feedback;

/**
 * FeedbackObserver Class
 *
 * This observer handles automatic actions related to the Feedback model lifecycle, primarily focusing on the `creating` event to generate unique tracking numbers.
 *
 * Its main responsibility is to assign a structured, human-readable tracking number in the format `FB-YYYYMMDD-XXXX` to each new feedback submission before it's saved.
 * This ensures every feedback entry has a distinct, chronologically ordered identifier that's easy to trace and reference for support and analytics.
 *
 * @package App\Observers
 */
final readonly class FeedbackObserver
{
    /**
     * Handle the Feedback "creating" event.
     *
     * This method is responsible for generating a unique, date-based tracking number for new Feedback submissions before they are saved to the database.
     * The format of the tracking number is `FB-YYYYMMDD-XXXX`, where:
     * - `FB` is a static prefix for Feedback.
     * - `YYYYMMDD` is the current date (e.g., 20250801).
     * - `XXXX` is a zero-padded sequential number for the current day, starting from 0001.
     *
     * @param  Feedback $feedback The Feedback model instance being created.
     */
    public function creating(Feedback $feedback): void
    {
        $today = now()->format('Ymd');  // Get the current date in YYYYMMDD format for the tracking number.
        $prefix = 'FB-' . $today . '-'; // e.g., 20250801-XXXX

        // Query the database to find the last tracking number generated for today.
        // We use `orderBy('tracking_number', 'desc')` to ensure we get the highest sequential number for the current day's prefix.
        $lastFeedback = Feedback::query()->where('tracking_number', 'like', $prefix . '%')
            ->orderBy('tracking_number', 'desc')
            ->first();

        // If previous feedback for today exists, extract its sequence number and increment it for the new submission.
        $sequence = 1;

        // If previous feedback for today exists, extract its sequence number and increment it for the new submission.
        if ($lastFeedback) {
            // The sequential part is always the last 4 characters of the tracking number.
            $lastSequence = (int) substr((string) $lastFeedback->tracking_number, -4);
            $sequence = $lastSequence + 1;
        }

        // Format the sequence number to be 4 digits, padded with leading zeros if necessary (e.g., 1 becomes 0001, 12 becomes 0012).
        $formattedSequence = str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);

        // Assign the newly generated tracking number to the Feedback model.
        // This will be saved to the database when the model is persisted.
        $feedback->tracking_number = $prefix . $formattedSequence;
    }
}
