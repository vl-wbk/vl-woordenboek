<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Relations\BelongsToAuthor;
use App\States\Reporting\Status;
use App\States\Reporting\ReportStateContract;
use App\States\Reporting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a report submitted for an article.
 *
 * The `ArticleReport` model is responsible for managing the lifecycle and relationships of reports submitted for dictionary articles.
 * It tracks the report's state, assignee, and associated article, and provides methods for transitioning between states in the reporting lifecycle.
 *
 * This model integrates with the state pattern to handle transitions between "Open", "In Progress", and "Closed" states.
 * It also uses Eloquent relationships to associate reports with articles, authors, and assignees.
 * The `state` attribute is cast to the `Status` enum, ensuring that the state is always represented as a strongly-typed value.
 *
 * Additionally, this model provides a foundation for future extensions, such as implementing an "Archived" state for reports.
 *
 * @package App\Models
 */
final class ArticleReport extends Model
{
    use BelongsToAuthor;

    /**
     * Specifies the attributes that are protected from mass assignment.
     *
     * The `guarded` property ensures that critical attributes, such as the report's ID, cannot be overwritten during mass assignment.
     * This helps maintain data integrity and prevents accidental modification of sensitive fields.
     *
     * @var array<string> The attributes that cannot be mass-assigned.
     */
    protected $guarded = ['id'];

    /**
     * Defines the default attributes for the model.
     *
     * When a new report is created, the `state` attribute is initialized to `Status::Open`.
     * This ensures that all new reports start in the "Open" state, awaiting action from administrators or moderators.
     *
     * @var array<string, mixed> The default attribute values.
     */
    protected $attributes = [
        'state' => Status::Open,
    ];

    /**
     * Defines the relationship between the report and the associated article.
     *
     * Each report is linked to a single article, allowing the system to track which article the report is related to.
     * This relationship is essential for providing context about the report and enabling administrators to address issues with specific articles.
     *
     * @return BelongsTo The relationship instance linking the report to its article.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Defines the relationship between the report and its assignee.
     *
     * Each report can be assigned to a single user, who is responsible for handling the report.
     * This relationship tracks the assignee for accountability and ensures that reports are addressed by the appropriate user.
     *
     * @return BelongsTo The relationship instance linking the report to its assignee.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Resolves the current state of the report.
     *
     * This method uses the state pattern to return an instance of the appropriate state class based on the current value of the `state` attribute.
     * The state classes define the behavior and transitions for each state in the reporting lifecycle.
     * For example, a report in the "Open" state will return an instance of the `OpenReportState` class, which implements the logic for transitioning to other states.
     *
     * This method also provides a foundation for extending the state system in the future, such as adding an "Archived" state for reports.
     *
     * @return ReportStateContract The state instance for the current report state.
     */
    public function status(): ReportStateContract
    {
        return match($this->state) {
            Status::Open => new Reporting\OpenReportState($this),
            Status::InProgress => new Reporting\ReportInProgressState($this),
            Status::Closed => new Reporting\ClosedReportState($this),
        };
    }

    /**
     * Defines the attribute casting for the model.
     *
     * The `state` attribute is cast to the `Status` enum, ensuring that the state is always represented as an instance of the `Status` enum when accessed.
     * This provides type safety and simplifies working with the state attribute throughout the application.
     *
     * @return array<string, string> The attribute casting configuration.
     */
    protected function casts(): array
    {
        return [
            'state' => Status::class,
        ];
    }
}
