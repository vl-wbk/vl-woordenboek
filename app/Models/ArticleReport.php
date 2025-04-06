<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Relations\BelongsToAuthor;
use App\States\Reporting\Status;
use App\States\Reporting\ReportStateContract;
use App\States\Reporting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ArticleReport extends Model
{
    use BelongsToAuthor;

    protected $guarded = ['id'];

    protected $attributes = [
        'state' => Status::Open,
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function status(): ReportStateContract
    {
        return match($this->state) {
            Status::Open => new Reporting\OpenReportState($this),
            Status::InProgress => new Reporting\ReportInProgressState($this),
            Status::Closed => new Reporting\ClosedReportState($this),
        };
    }

    protected function casts(): array
    {
        return [
            'state' => Status::class,
        ];
    }
}
