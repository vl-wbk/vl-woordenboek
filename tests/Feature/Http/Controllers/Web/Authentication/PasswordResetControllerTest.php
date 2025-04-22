<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('it can render the password link screen', function (): void {
    get('/forgot-password')->assertSuccessful();
});

test('reset password link can be requested', function (): void {
    Notification::fake();

    $user = User::factory()->create();

    post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('it can render the reset password screen')->todo();
test('password can be reset with valid token')->todo();
