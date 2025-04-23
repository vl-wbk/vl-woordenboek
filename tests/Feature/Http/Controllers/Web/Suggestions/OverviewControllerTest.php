<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('It redirect the user to the login screen then unauthenticated', function (): void {
    get(route('suggestions:index'))->assertRedirect(route('login'));
});

test('It can successfully display the page when the user is authenticated and has suggestions', function (): void {
    Article::factory(6)->create();
    $user = User::factory()->create();

    actingAs($user)->get(route('suggestions:index'))->assertSuccessful();
});

test('It can successfully display the page when the user is authenticated and has no suggestions')->todo();
test('It can successfully display the page when the search term is present')->todo();
test('It can successfully display the page when the user wants in new suggestions')->todo();
test('It can successfully display the page when the user wants his suggestions that are in progress')->todo();
test('it can successfully display the page when the user wants his suggesions that are finalized')->todo();

