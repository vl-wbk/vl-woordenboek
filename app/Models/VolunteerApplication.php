<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VolunteerApplicationState;
use App\Enums\VolunteerPositions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int                        $id 
 * @property VolunteerApplicationState  $state
 * @property int                        $volunteer_id
 * @property ?int                       $reviewer_id
 * @property VolunteerPositions         $role
 * @property string                     $motivation 
 * @property string                     $background 
 * @property ?string                    $rejection_reason
 * @property ?Carbon                    $created_at 
 * @property ?Carbon                    $updated_at
 * 
 * @property-read User $user
 * @property-read User $reviewer
 * 
 * @package App\Models 
 */
final class VolunteerApplication extends Model
{
    use HasFactory;
    
    protected $guarded = ['id', 'user_id'];

    protected $attributes = [
        'state' => VolunteerApplicationState::Open,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    protected function casts(): array 
    {
        return [
            'state' => VolunteerApplicationState::class, 
            'role' => VolunteerPositions::class,
        ];
    }
}
