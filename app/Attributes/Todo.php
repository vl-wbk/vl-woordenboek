<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute(
    Attribute::TARGET_CLASS    |
    Attribute::TARGET_METHOD   |
    Attribute::TARGET_PROPERTY |
    Attribute::TARGET_FUNCTION |
    Attribute::IS_REPEATABLE
)]
class Todo
{
    public function __construct(
        public readonly string  $message,
        public readonly string  $author   = '',
        public readonly string  $priority = 'normal',  // low | normal | high
        public readonly string  $issue    = '',         // e.g. GH-42
        public readonly ?string $due      = null,       // e.g. 2025-06-01
        public readonly array   $tags     = [],         // e.g. ['security', 'refactor']
    ) {}
}