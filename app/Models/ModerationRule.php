<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModerationRule extends Model
{
    protected $fillable = [
        'pattern',
        'category',
        'explanation',
        'neutral_alternative',
        'is_regex',
        'allowed_contexts',
        'forbidden_contexts',
    ];

    protected $casts = [
        'is_regex' => 'boolean',
    ];
}
