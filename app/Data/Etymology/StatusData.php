<?php

declare(strict_types=1);

namespace App\Data\Etymology;

use App\Enums\Articles\EtymologyStatus;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

final class StatusData extends Data
{
	public function __construct(
		public readonly EtymologyStatus $status,
		public readonly string|int|null $archived_by = null,
		public readonly string|int|null $rejected_by = null,
		public readonly string|int|null $published_by = null,
		public readonly ?Carbon $published_at = null,
		public readonly ?Carbon $rejected_at = null,
		public readonly ?Carbon $archived_at = null,
		public ?string $archiving_reason = null,
		public ?string $rejection_reason = null,
	) {}
}