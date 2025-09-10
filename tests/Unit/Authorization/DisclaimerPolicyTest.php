<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use Mockery;
use App\Models\User;
use App\Models\Disclaimer;
use App\Policies\DisclaimerPolicy;

beforeEach(function (): void {
    $this->policy = new DisclaimerPolicy();
});

test('de before methode weigert de toegang als de gebruiker de page_Articles permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('cannot')->once()->with('page_Articles')->andReturn(true);

    $response = $this->policy->before($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe(expected: __('disclaimer-resource.policy.deny-messages.before'));
});

test('de before methode staat de toegang toe als de gebruiker de page_Articles permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('cannot')->once()->with('page_Articles')->andReturn(false);

    $response = $this->policy->before($user);

    expect($response)->toBeNull();
});

test('de viewAny methode staat het bekijken van alle disclaimers toe als de gebruiker de view_any_disclaimer permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('view_any_disclaimer')->andReturn(true);

    $response = $this->policy->viewAny($user);

    expect($response->allowed())->toBeTrue();
});

test('de viewAny methode weigert het bekijken van alle disclaimers als de gebruiker de permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('view_any_disclaimer')->andReturn(false);

    $response = $this->policy->viewAny($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe(expected: __('disclaimer-resource.policy.deny-messages.viewAny'));
});

test('de view methode staat het bekijken van een specifieke disclaimer toe als de gebruiker de view_disclaimer permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $disclaimer = Mockery::mock(Disclaimer::class);
    $user->shouldReceive('can')->once()->with('view_disclaimer')->andReturn(true);

    $response = $this->policy->view($user, $disclaimer);

    expect($response->allowed())->toBeTrue();
});

test('de view methode weigert het bekijken van een specifieke disclaimer als de gebruiker de permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $disclaimer = Mockery::mock(Disclaimer::class);
    $user->shouldReceive('can')->once()->with('view_disclaimer')->andReturn(false);

    $response = $this->policy->view($user, $disclaimer);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe(expected: __('disclaimer-resource.policy.deny-messages.view'));
});

test('de create methode staat het aanmaken van een disclaimer toe als de gebruiker de create_disclaimer permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('create_disclaimer')->andReturn(true);

    $response = $this->policy->create($user);

    expect($response->allowed())->toBeTrue();
});

test('de create methode weigert het aanmaken van een disclaimer als de gebruiker de permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('create_disclaimer')->andReturn(false);

    $response = $this->policy->create($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe(expected : __('disclaimer-resource.policy.deny-messages.create'));
});

test('de update methode staat het bijwerken van een disclaimer toe als de gebruiker de update_disclaimer permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('update_disclaimer')->andReturn(true);

    $response = $this->policy->update($user);

    expect($response->allowed())->toBeTrue();
});

test('de update methode weigert het bijwerken van een disclaimer als de gebruiker de permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('update_disclaimer')->andReturn(false);

    $response = $this->policy->update($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe(expected: __('disclaimer-resource.policy.deny-messages.update'));
});

test('de delete methode staat het verwijderen van een disclaimer toe als de gebruiker de delete_disclaimer permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_disclaimer')->andReturn(true);

    $response = $this->policy->delete($user);

    expect($response->allowed())->toBeTrue();
});

test('de delete methode weigert het verwijderen van een disclaimer als de gebruiker de permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_disclaimer')->andReturn(false);

    $response = $this->policy->delete($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe(expected: __('disclaimer-resource.policy.deny-messages.delete'));
});

test('de deleteAny methode staat het in bulk verwijderen van disclaimers toe als de gebruiker de delete_any_disclaimer permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_any_disclaimer')->andReturn(true);

    $response = $this->policy->deleteAny($user);

    expect($response->allowed())->toBeTrue();
});

test('de deleteAny methode weigert het in bulk verwijderen van disclaimers als de gebruiker de permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_any_disclaimer')->andReturn(false);

    $response = $this->policy->deleteAny($user);

    expect($response->denied())->toBeTrue()
        ->and($response->message())->toBe(expected: __('disclaimer-resource.policy.deny-messages.deleteAny'));
});
