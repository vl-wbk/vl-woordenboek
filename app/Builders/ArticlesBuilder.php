<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

final class ArticlesBuilder extends Builder
{
    public function __construct($query)
    {
        parent::__construct($query);
    }
}
