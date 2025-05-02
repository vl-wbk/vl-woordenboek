<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('an unauthenticated user can view the dictionary article', function (): void {
    $article = Article::factory()->create(['published_at' => now()]);
    $user = User::factory()->create();

    get(route('word-information.show', ['word' => $article]))
        ->assertSuccessful();

    actingAs($user)->get(route('word-information.show', ['word' => $article]))
        ->assertSuccessful();
});

it('it throws an http not found when an invalid article has been given', function (): void {
    $user = User::factory()->create();

    actingAs($user)->get(route('word-information.show', ['word' => '777']))
        ->assertNotFound();
});
