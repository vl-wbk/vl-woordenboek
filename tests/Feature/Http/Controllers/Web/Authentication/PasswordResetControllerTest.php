<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

/**
 * Tests that the 'Forgot Password' page can be successfully rendered.
 * This ensures that the initial view for requesting a password reset link is accessible to users.
 */
test('it can render the password link screen', function (): void {
    // Act: Attempt to access the '/forgot-password' route.
    get('/forgot-password')->assertSuccessful();
});

test('reset password link can be requested', function (): void {
    // Arrange: Set up a fake notification facade to intercept sent notifications and create a user.
    Notification::fake();
    $user = User::factory()->create();

    // Act: Simulate a POST request to the '/forgot-password' route with the user's email.
    post('/forgot-password', ['email' => $user->email]);

    // Assert: Check if a ResetPassword notification was sent to the created user.
    Notification::assertSentTo($user, ResetPassword::class);
});

/**
 * Tests that a password reset link can be requested for a valid email address.
 * This verifies that when a user submits their email on the forgot password page, a password reset notification is dispatched to that user.
 */
test('it can render the reset password screen', function (): void {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);
        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function (): void {
    Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
});
