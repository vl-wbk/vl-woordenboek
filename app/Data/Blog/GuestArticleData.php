<?php

declare(strict_types=1);

namespace App\Data\Blog;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class GuestArticleData extends Data
{
	public function __construct(
		#[MapInputName('titel')]
		public readonly string $title,
		#[MapInputName('artikel')]
		public readonly string $content,
		#[MapInputName('url')]
		public readonly ?string $original_url = null,
	) {}
}