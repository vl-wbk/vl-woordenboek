<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Articles\EtymologyStatus;
use App\Enums\Articles\EtymologyTypes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Etymology extends Model
{
    protected $guarded = ['id'];

    protected $attributes = [
        'status' => EtymologyStatus::UnderReview,
    ];

    public function period(): Attribute
    {
        return Attribute::get(fn () => $this->period_start->format('d/m/Y') . ' - ' . $this->period_end->format('d/m/Y'));
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    protected function casts(): array
    {
        return [
            'period_end' => 'date',
            'period_start' => 'date',
            'status' => EtymologyStatus::class,
            'type' => EtymologyTypes::class,
        ];
    }
}
