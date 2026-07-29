<?php

declare(strict_types=1);

namespace App\Models;

use App\Attributes\Todo;
use App\States\Reporting\OpenReportState;
use App\States\Reporting\ReportInProgressState;
use App\States\Reporting\ClosedReportState;
use App\Models\Relations\BelongsToAuthor;
use App\States\Reporting\Status;
use App\States\Reporting\ReportStateContract;
use Carbon\Carbon;
use Database\Factories\ArticleReportFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a report submitted for an article.
 *
 * The `ArticleReport` model is responsible for managing the lifecycle and relationships of reports submitted for dictionary articles.
 * It tracks the report's state, assignee, and associated article and provides methods for transitioning between states in the reporting lifecycle.
 *
 * This model integrates with the state pattern to handle transitions between "Open", "In Progress", and "Closed" states.
 * It also uses Eloquent relationships to associate reports with articles, authors, and assignees.
 * The `state` attribute is cast to the `Status` enum, ensuring that the state is always represented as a strongly typed value.
 *
 * Additionally, this model provides a foundation for future extensions, such as implementing an "Archived" state for reports.
 *
 * @property int         $id            Unique identifier for the report.
 * @property Status      $state         Current status of the report (e.g., pending, reviewed, resolved).
 * @property int         $assignee_id   ID of the user assigned to review the report.
 * @property ?int        $author_id     ID of the user who submitted the report.
 * @property ?int        $article_id    ID of the article being reported.
 * @property string      $description   Detailed description of the reported issue.
 * @property Carbon|null $assigned_at   Date and time when the report was assigned for review.
 * @property Carbon|null $closed_at     Date and time when the report was resolved/closed.
 * @property Carbon|null $created_at    Date and time when the report was submitted.
 * @property Carbon|null $updated_at    Date and time when the report was last updated.
 *
 * @package App\Models
 */
#[Guarded(columns: ['id'])]
final class ArticleReport extends Model
{
    /** @use HasFactory<ArticleReportFactory> */
    use HasFactory;
    use BelongsToAuthor;

    /**
     * Defines the default attributes for the model.
     *
     * When a new report is created, the `state` attribute is initialized to `Status::Open`.
     * This ensures that all new reports start in the "Open" state, awaiting action from administrators or moderators.
     *
     * @var array<string, Status> The default attribute values.
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
     * @return BelongsTo<Article, covariant $this> The relationship instance linking the report to its article.
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Defines the relationship between the report and its assignee.
     *
     * Each report can be assigned to a single user, who is responsible for handling the report.
     * This relationship tracks the assignee for accountability and ensures that reports are addressed by the appropriate user.
     *
     * @return BelongsTo<User, covariant $this> The relationship instance linking the report to its assignee.
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
    #[Todo(message: 'Refactor this to FusionState actions.')]
    public function status(): ReportStateContract
    {
        return match ($this->state) {
            Status::Open => new OpenReportState($this),
            Status::InProgress => new ReportInProgressState($this),
            Status::Closed => new ClosedReportState($this),
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
