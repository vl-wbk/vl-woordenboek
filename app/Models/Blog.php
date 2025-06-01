<?php

declare(strict_types=1);

namespace App\Models;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Blog extends Model
{
    use HasFactory;
    use HasUlids;

    protected $attributes = [
        'status' => Status::Draft,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withDefault(callback: [
                'name' => config('app.name')
            ]);
    }

    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }
}
