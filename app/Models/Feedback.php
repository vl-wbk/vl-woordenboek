<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FeedbackFactory;
use App\Enums\FeedbackTrueFalse;
use App\Enums\FeedbackStatus;
use App\Models\Relations\BelongsToAuthor;
use App\Observers\FeedbackObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The feedback model represents user feedback submissions in the system.
 *
 * This model stores various types of feedback collected from users, including information about their visit experience, contact preferences, and additional comments.
 * The feedback helps improve the app's usability and user experience.
 *
 * @property int                $id                       Unique identifier for the feedback entry
 * @property string             $tracking_number          THe unique tracking number for the feedback submission. Can be used on GitHub commits and such.
 * @property ?int               $author_id                The unique identifier form the user who created the feedback submission, null if no user is authenticated
 * @property string             $name                     The first and last name of the user who submitted the feedback
 * @property FeedbackTrueFalse  $first_time_visit         Indicates if this the user's first visit
 * @property FeedbackTrueFalse  $results_found_easily     Indicates if the user found they were looking for
 * @property FeedbackStatus     $status                   The current status from the feedback submission.
 * @property ?string            $email                    Optional email address for follow-up contact
 * @property ?string            $visit_reason             The user's reason for visiting the website
 * @property ?string            $search_additional_info   Additional information about what the user was searching for
 * @property ?bool              $contact_allowed          Whether the user allows follow-up context
 * @property Carbon|null        $created_at               Timestamp when the feedback was submitted
 * @property Carbon|null        $updated_at               Timestamp when the feedback was last updated
 *
 * @package App\Models
 */
#[Guarded(columns: ['id', 'author_id'])]
#[ObservedBy(classes: FeedbackObserver::class)]
class Feedback extends Model
{
    /** @use HasFactory<FeedbackFactory> */
    use HasFactory;
    use BelongsToAuthor;

    /**
     * The model's default attribute values.
     *
     * These values are applied when a new model instance is created, ensuring that certain attributes have a predefined initial state.
     * For example, new feedback submissions are set to 'Unprocessed' by default.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => FeedbackStatus::Unprocessed,
    ];

    /**
     * Get the casts for the model.
     *
     * The `casts` method defines how certain attributes should be converted to native PHP types when they are retrieved from your database.
     * This ensures type safety and simplifies working with enums and boolean values.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => FeedbackStatus::class,
            'contact_allowed' => 'boolean',
            'first_time_visit' => FeedbackTrueFalse::class,
            'results_found_easily' => FeedbackTrueFalse::class,
        ];
    }
}
