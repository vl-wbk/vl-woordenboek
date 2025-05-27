<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The feedback model represents user feedback submissions in the system.
 *
 * This model stores various types of feedback collected from users, including information about their visit experience, contact preferences, and additional comments.
 * The feedback helps improve the application's usability and user experience.
 *
 * @property int                $id                       Unique identifier for the feedback entry
 * @property ?int               $author_id                The unique identifier form the user who created the feedback submission, null if no user is authenticated
 * @property string             $name                     The first and last name of the user who submitted the feedback
 * @property FeedbackTrueFalse  $first_time_visit         Indicates if this the user's first visit
 * @property FeedbackTrueFalse  $results_found_easily     Indicates if the user found they were looking for
 * @property ?string            $email                    Optional email address for follow-up contact
 * @property ?string            $visit_reason             The user's reason for visiting the website
 * @property ?string            $search_additional_info   Additional information about what the user was searching for
 * @property ?bool              $contact_allowed          Whether the user allows follow-up context
 * @property \Carbon\Carbon     $created_at               Timestamp when the feedback was submitted
 * @property \Carbon\Carbon     $updated_at               Timestamp when the feedback was last updated
 *
 * @method author() The data relation for the user who created the feedback submission
 *
 * @package App\Models
 */
final class Feedback extends Model
{
    /**
     * @var list<sttring>
     */
    protected $guarded = ['id', 'author_id'];

    /**
     * Get the user who submitted this feedback.
     *
     * 
     * @return BelongsTo<User, covariant $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
