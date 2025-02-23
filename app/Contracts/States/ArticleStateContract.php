<?php

namespace App\Contracts\States;

interface ArticleStateContract
{
    public function transitionToEditing(): void;

    public function transitionToApproved(): void;

    public function transitionToReleased(): void;

    public function transitionToArchived(): void;
}
