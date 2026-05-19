<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Relations\BelongsToAuthor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('description', 'reason')]
final class CorrectionProposal extends Model 
{
    use BelongsToAuthor;
}
