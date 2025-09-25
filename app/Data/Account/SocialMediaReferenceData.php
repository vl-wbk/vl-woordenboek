<?php

declare(strict_types=1);

namespace App\Data\Account;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class SocialMediaReferenceData extends Data
{
	public function __construct(
		#[MapInputName('twitter')]
		public readonly ?string $twitter = null,
		#[MapInputName('bluesky')]
		public readonly ?string $bluesky = null,
		#[MapInputName('website')]
		public readonly ?string $website = null,
	) {}
}