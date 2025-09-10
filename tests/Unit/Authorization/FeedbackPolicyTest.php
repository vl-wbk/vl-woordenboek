<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use Mockery;
use App\Models\User;
use App\Models\Feedback;
use App\Policies\FeedbackPolicy;

beforeEach(function (): void {
    $this->policy = new FeedbackPolicy();
});

test('de viewAny methode staat de toegang toe als de gebruiker de view_any_feedback permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('view_any_feedback')->andReturn(true);

    $response = $this->policy->viewAny($user);

    expect($response->allowed())->toBeTrue();
});

test('de viewAny methode weigert de toegang als de gebruiker de view_any_feedback permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('view_any_feedback')->andReturn(false);

    $response = $this->policy->viewAny($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe('U hebt geen machtiging om het feedback overzicht te bekijken.');
});

test('de view methode staat de toegang toe als de gebruiker de view_feedback permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $feedback = Mockery::mock(Feedback::class);
    $user->shouldReceive('can')->once()->with('view_feedback')->andReturn(true);

    $response = $this->policy->view($user, $feedback);

    expect($response->allowed())->toBeTrue();
});

test('de view methode weigert de toegang als de gebruiker de view_feedback permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $feedback = Mockery::mock(Feedback::class);
    $user->shouldReceive('can')->once()->with('view_feedback')->andReturn(false);

    $response = $this->policy->view($user, $feedback);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe('U hebt geen machtiging om dit feedback bericht te bekijken.');
});

test('de delete methode staat de toegang toe als de gebruiker de delete_feedback permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_feedback')->andReturn(true);

    $response = $this->policy->delete($user);

    expect($response->allowed())->toBeTrue();
});

test('de delete methode weigert de toegang als de gebruiker de delete_feedback permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_feedback')->andReturn(false);

    $response = $this->policy->delete($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe('U hebt geen machtiging om dit feedback bericht te verwijderen.');
});

test('de markAs methode staat de toegang toe als de gebruiker de change_status_feedback permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('change_status_feedback')->andReturn(true);

    $response = $this->policy->markAs($user);

    expect($response->allowed())->toBeTrue();
});

test('de markAs methode weigert de toegang als de gebruiker de change_status_feedback permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('change_status_feedback')->andReturn(false);

    $response = $this->policy->markAs($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe('U hebt geen machtiging om dit feedback bericht te markeren als opgelost.');
});

test('de deleteAny methode staat de toegang toe als de gebruiker de delete_any_feedback permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_any_feedback')->andReturn(true);

    $response = $this->policy->deleteAny($user);

    expect($response->allowed())->toBeTrue();
});

test('de deleteAny methode weigert de toegang als de gebruiker de delete_any_feedback permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_any_feedback')->andReturn(false);

    $response = $this->policy->deleteAny($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe('U hebt geen machtiging om feedback te verwijderen.');
});
