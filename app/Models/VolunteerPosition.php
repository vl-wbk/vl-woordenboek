<?php

declare(strict_types=1);

namespace App\Models;

use App\UserTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

final class VolunteerPosition extends Model
{
    use HasRoles;

    protected $guarded = ['id'];

    public function applications(): HasMany
    {
        return $this->hasMany(VolunteerApplications::class);
    }

    protected function casts(): array
    {
        return [
            'associated_user_group' => UserTypes::class,
        ];
    }
}
