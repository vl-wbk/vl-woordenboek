<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DisclaimerTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property DisclaimerTypes $type
 */
final class Disclaimer extends Model
{
    protected $fillable = ['type', 'name', 'message', 'usage', 'description'];

    protected $attributes = [
        'type' => DisclaimerTypes::Default,
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    protected function casts(): array
    {
        return [
            'type' => DisclaimerTypes::class,
        ];
    }
}
