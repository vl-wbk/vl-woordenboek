<?php

use App\Livewire\ReportArticleModal;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ReportArticleModal::class)
        ->assertStatus(200);
});
