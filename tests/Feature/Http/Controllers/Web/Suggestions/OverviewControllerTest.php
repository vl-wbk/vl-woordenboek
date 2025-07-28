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

test('It can successfully display the page when the user is authenticated and has no suggestions', function (): void {
    $user = User::factory()->create();
    actingAs($user)->get(route('suggestions:index'))->assertSuccessful();
});

test('It can successfully display the page when the search term is present', function (): void {
    Article::factory(6)->create();
    $user = User::factory()->create();

    actingAs($user)->get(route('suggestions:index', ['zoekterm' => 'searchTerm']))->assertSuccessful();
});

test('It can successfully display the page when the user wants in new suggestions', function (): void {
    Article::factory(6)->create();
    $user = User::factory()->create();

    actingAs($user)->get(route('suggestions:index', ['filter' => 'new']))->assertSuccessful();
});

test('It can successfully display the page when the user wants his suggestions that are in progress', function (): void {
    Article::factory(6)->create();
    $user = User::factory()->create();

    actingAs($user)->get(route('suggestions:index', ['filter' => 'inProgress']))->assertSuccessful();
});

test('it can successfully display the page when the user wants his suggesions that are finalized', function (): void {
    Article::factory(6)->create();
    $user = User::factory()->create();

    actingAs($user)->get(route('suggestions:index', ['filter' => 'done']))->assertSuccessful();
});

