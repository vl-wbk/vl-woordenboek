<?php

use function Pest\Laravel\from;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('registration screen can be rendered', function (): void {
    get(route('register'))->assertSuccessful();
});

test('new users can register', function (): void {
    $response = from('/')->post(route('register'), data: [
        'voornaam' => 'Test',
        'achternaam' => 'User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'agreement' => 'on'
    ]);

    $response->assertSessionHasNoErrors();
})->group('registration');
