<?php

namespace App\Contracts\States\ArticleStates;

interface ArticleStateContract
{
    public function transitionToEditing(): void;

    public function transitionToApproved(): void;

    public function transitionToReleased(): void;

    public function transitionToArchived(): void;
}
