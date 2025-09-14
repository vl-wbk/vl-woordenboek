<?php

declare(strict_types=1);

namespace App\States\Posts;

use App\Exceptions\StateTransitionException;

final class GuestArticle extends PublicationState
{
	/**
	 * @throws StateTransitionException
	 */
	public function transitionToDraft(): bool
	{
		throw new StateTransitionException('Cannot transition to the draft state on the current state');
	}
}