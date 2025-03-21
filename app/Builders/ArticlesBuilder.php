<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\ArticleStates;
use App\Enums\Visibility;
use Illuminate\Database\Eloquent\Builder;

final class ArticlesBuilder extends Builder
{
    public function __construct($query)
    {
        parent::__construct($query);
    }

    public function published(): Builder
    {
        return $this->where('visibility', Visibility::Published);
    }

    public function transitionState(ArticleStates $articleState, Visibility $visibility = Visibility::Draft): self
    {
        $this->model->update(['state' => $articleState, 'visibility' => $visibility]);

        return $this;
    }
}
