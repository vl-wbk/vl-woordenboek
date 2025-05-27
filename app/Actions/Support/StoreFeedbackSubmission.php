<?php

declare(strict_types=1);

namespace App\Actions\Support;

use App\Data\FeedbackSubmissionData;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Handles the storage of user feedback submissions in the application.
 *
 * The StoreFeedbackSubmission action serves as a dedicated handler for persisting user feedback in the database.
 * It ensures data integrity by wrapping the creation process in a database transaction and maintains proper relationships by associating the feedback with the authenticated user.
 * This action is designed to work with validated feedback data through the FeedbackSubmissionData object, providing a reliable way to store  user feedback while maintaining data consistency.
 *
 * @package App\Actions\Support
 */
final readonly class StoreFeedbackSubmission
{
    /**
     * Stores the feedback submission in the database.
     *
     * This method handles the complete process of storing user feedback. It begins by creating a new feedback record using the provided submission data.
     * Then, it establishes the relationship between the feedback and the currently authenticated user.
     * All operations occur within a database transaction to ensure data consistency.
     *
     * The transaction ensures that both the feedback creation and user association succeed together, or neither operation takes place.
     * This prevents orphaned or incorrectly associated feedback entries in the database.
     *
     * @param  FeedbackSubmissionData $feedbackSubmissionData   The validated feedback data to be stored
     * @return Feedback                                         The newly created and associated feedback entry
     *
     * @throws \Throwable If the database transaction fails or user association cannot be completed
     */
    public function execute(FeedbackSubmissionData $feedbackSubmissionData): Feedback
    {
        return DB::transaction(function () use ($feedbackSubmissionData): Feedback {
            $feedback = Feedback::create(attributes: $feedbackSubmissionData->toArray());
            $feedback->author()->associate(Auth::user())->save();

            return $feedback;
        });
    }
}
