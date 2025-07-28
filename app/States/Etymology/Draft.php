<?php

declare(strict_types=1);

namespace App\States\Etymology;

final readonly class Draft extends EtymologyState
{
	public function transitionToDraft(): bool
	{
		return false;
	}
	
	public function transitionToRejected(?string $reason = null): bool
	{
		return false;
	}
	
	public function transitionToPublished(): bool
	{
		return false;
	}
}