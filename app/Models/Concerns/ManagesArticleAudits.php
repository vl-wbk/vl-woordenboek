<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use OwenIt\Auditing\Auditable;

trait ManagesArticleAudits
{
    use Auditable;

    public ?string $correctionReason = null;

    public function transformAudit(array $data): array
{
    if ($this->correctionReason === null) {
        // Not a correction submission (e.g. admin edit) — leave the audit untouched
        return $data;
    }

    $data['correction_reason'] = $this->correctionReason;

    return $data;
}
}
