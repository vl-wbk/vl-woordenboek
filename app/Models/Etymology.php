<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Articles\EtymologyStatus;
use App\Enums\Articles\EtymologyTypes;
use App\Models\Relations\BelongsToAuthor;
use App\Observers\EtymologyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property EtymologyStatus $status
 * @property EtymologyTypes|null $type
 * @property int|null $article_id
 * @property string $origin_language
 * @property string $origin_form
 * @property string $source
 * @property string $source_url
 * @property string|null $note
 * @property string $etymology
 * @property \Illuminate\Support\Carbon|null $period_start
 * @property \Illuminate\Support\Carbon|null $period_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property User $author
 *
 *
 * @package App\Models
 */
#[ObservedBy(EtymologyObserver::class)]
final class Etymology extends Model
{
    use BelongsToAuthor;

    protected $guarded = ['id'];

    protected $attributes = [
        'status' => EtymologyStatus::UnderReview,
    ];

    /**
     * @return Attribute<non-falsy-string, never>
     */
    public function period(): Attribute
    {
        return Attribute::get(fn () => $this->period_start->format('d/m/Y') . ' - ' . $this->period_end->format('d/m/Y'));
    }
    
    public function archiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by')
            ->withDefault(['name' => 'Onbekende of verwijderde gebruiker']);
    }
    
    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by')
            ->withDefault(['name' => 'Onbekende of verwijderde gebruiker']);
    }

    /**
     * @return BelongsTo<Article, covariant $this>
     */
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
