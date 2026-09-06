<?php

namespace App\Domain\Quality;

enum ArticleReviewDecision: string
{
    case Approved = 'approved';
    case Corrected = 'corrected';
    case Rejected = 'rejected';
    case NeedsChanges = 'needs_changes';
}
