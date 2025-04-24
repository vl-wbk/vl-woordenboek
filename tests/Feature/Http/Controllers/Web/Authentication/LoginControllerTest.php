<?php

use App\Models\User;

test('it can render the login screen successfully', function (): void {
    $this->get(route('login'))->assertSuccessful();
});

test('users can authenticated using the login screen', function (): void {
    $user = User::factory()->create();

    $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('home'));

    expect(auth()->check())->toBeTrue();
});

test('users can not authenticated with invalid passwords', function (): void {
    $user = User::factory()->create();

    $this->from(route('login'))
        ->post(route('login'), ['email' => $user->email, 'password' => 'faulty-password'])
        ->assertRedirect('login');

    expect(auth()->check())->toBeFalse();
});
