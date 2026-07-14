<?php

declare(strict_types=1);

namespace App\Models\Relations\Articles;

use App\Models\CorrectionProposal;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasCorrectionSupport
{
    /**
     * @return HasMany<CorrectionProposal, covariant $this>
     */
    public function corrections(): HasMany
    {
        return $this->hasMany(CorrectionProposal::class);
    }
}
