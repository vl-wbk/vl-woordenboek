<?php

use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('registration screen can be rendered', function (): void {
    get(route('register'))->assertSuccessful();
});

test('new users can register', function (): void {
    $response = post(route('register'), data: [
        'voornaam' => 'Test',
        'achternaam' => 'User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('home'));

    expect(auth()->check())->toBeTrue();
});
