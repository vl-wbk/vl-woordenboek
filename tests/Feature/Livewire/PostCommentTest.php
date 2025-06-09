<?php

use App\Livewire\PostComment;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(PostComment::class)
        ->assertStatus(200);
});
