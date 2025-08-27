<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasLabel;

/**
 * Represents the processing status of a feedback item.
 *
 * This enum defines the two primary states for feedback: `Unprocessed` (new feedback) and `Processed` (feedback that has been reviewed or handled).
 *
 * It implements the `HasLabel` interface from Filament, allowing for easy
 * display of human-readable labels in the user interface.
 * It also uses the `Comparable` trait from `ArchTech\Enums` for convenient comparison operations between enum instances.
 *
 * @package App\Enums
 */
enum FeedbackStatus: int implements HasLabel
{
    use Comparable;

    /**
     * Represents feedback that has not yet been reviewed or acted upon.
     * This is typically the initial state for any newly submitted feedback.
     * Items in this state are awaiting review by an administrator or designated team member.
     */
    case Unprocessed = 0;

    /**
     * Represents feedback that has been reviewed, handled, or otherwise processed.
     * This state indicates that the feedback has been acknowledged, addressed,
     * or taken into consideration. It might involve actions like replying to the user,
     * creating a task, or implementing a suggested change.
     */
    case Processed = 1;

    /**
     * Retrieves the human-readable label for the current feedback status.
     * This method provides a concise, display-friendly name for each enum case, suitable for use in dropdowns, labels, or table columns.
     *
     * @return string The localized label of the feedback status.
     */
    public function getLabel(): string
    {
		return match ($this) {
			self::Unprocessed => __('feedback-resource.statuses.unprocessed'),
			self::Processed => __('feedback-resource.statuses.processed'),
		};
    }
}
