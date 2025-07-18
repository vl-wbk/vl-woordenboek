<?php

declare(strict_types=1);

namespace App\States\Etymology;

interface EtymologyStateContract
{
	public function transitionToDraft(): bool;
	
	public function transitionToUnderReview(): bool;
	
	public function transitionToRejected(?string $reason = null): bool;
	
	public function transitionToPublished(): bool;
	
	public function transitionToArchived(?string $reason = null): bool;
}
