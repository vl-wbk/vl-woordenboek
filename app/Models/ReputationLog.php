<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class ReputationLog extends Model
{
    protected $guarded = ['id'];


    public function resource(): MorphTo
    {
        return $this->morphTo();
    }
}
