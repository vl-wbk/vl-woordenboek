<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

it('can successfully render the dictionary search results page', function (): void {
    actingAs($this->user)->get(route('search.results'))->assertSuccessful();
    get(route('search.results'))->assertSuccessful();
})->group('articles');

it('can successfully render the dictionary search results page while the search param is present', function (): void {
    actingAs($this->user)->get(route('home', ['zoekterm' => 'test']))->assertSuccessful();
    get(route('home', ['zoekterm' => 'test']))->assertSuccessful();
})->group('articles');
