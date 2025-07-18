<?php

declare(strict_types=1);

namespace App\States\Etymology;

use App\Data\Etymology\StatusData;
use App\Enums\Articles\EtymologyStatus;
use App\Models\Etymology;
use Illuminate\Support\Facades\DB;

readonly class EtymologyState implements EtymologyStateContract
{
	public function __construct(
		public Etymology $etymology,
	) {}
	
	public function transitionToDraft(): bool
	{
		return DB::transaction(fn (): bool => $this->etymology->update(
			attributes: StatusData::from(['status' => EtymologyStatus::Draft])->toArray())
		);
	}
	
	public function transitionToUnderReview(): bool
	{
		return DB::transaction(fn (): bool => $this->etymology->update(
			attributes: StatusData::from(['status' => EtymologyStatus::UnderReview])->toArray())
		);
	}
	
	public function transitionToRejected(?string $reason = null): bool
	{
		return DB::transaction(function () use ($reason): bool {
			return $this->etymology->update(attributes: StatusData::from([
				'status' => EtymologyStatus::Rejected,
				'rejected_by' => auth()->user()->getAuthIdentifier(),
				'rejected_at' => now(),
				'rejection_reason' => $reason,
			])->toArray());
		});
	}
	
	public function transitionToPublished(): bool
	{
		return DB::transaction(fn(): bool =>
			$this->etymology->update(attributes: StatusData::from([
				'status' => EtymologyStatus::Published,
				'published_at' => now(),
				'published_by' => auth()->user()->getAuthIdentifier()
			])->toArray())
		);
	}
	
	public function transitionToArchived(?string $reason = null): bool
	{
		return DB::transaction(function () use ($reason): bool {
			return $this->etymology->update(attributes: StatusData::from([
				'status' => EtymologyStatus::Archived,
				'archived_by' => auth()->user()->getAuthIdentifier(),
				'archived_at' => now(),
				'archiving_reason' => $reason,
			])->toArray());
		});
	}
}