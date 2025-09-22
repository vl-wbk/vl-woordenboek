<?php

namespace App\Data\Message;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class ReplyDataObject extends Data
{
	public function __construct(
		#[MapInputName('bericht')]
		public readonly string $message,
	) {}
}