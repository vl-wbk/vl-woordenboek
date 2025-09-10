<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use Mockery;
use App\Models\User;
use App\Policies\RolePolicy;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    $this->policy = new RolePolicy();
});

test('de before methode weigert de toegang als de gebruiker de page_UserManagement permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('cannot')->once()->with('page_UserManagement')->andReturn(true);

    $response = $this->policy->before($user, 'some_ability');

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->status())->toBe(404);
});

test('de before methode staat de toegang toe als de gebruiker de page_UserManagement permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('cannot')->once()->with('page_UserManagement')->andReturn(false);

    $response = $this->policy->before($user, 'some_ability');

    expect($response)->toBeNull();
});

test('de viewAny methode staat de toegang toe als de gebruiker de view_any_role permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('view_any_role')->andReturn(true);

    $result = $this->policy->viewAny($user);

    expect($result)->toBeTrue();
});

test('de viewAny methode weigert de toegang als de gebruiker de view_any_role permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('view_any_role')->andReturn(false);

    $result = $this->policy->viewAny($user);

    expect($result)->toBeFalse();
});

test('de view methode staat de toegang toe als de gebruiker de view_role permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $role = Mockery::mock(Role::class);
    $user->shouldReceive('can')->once()->with('view_role')->andReturn(true);

    $result = $this->policy->view($user, $role);

    expect($result)->toBeTrue();
});

test('de view methode weigert de toegang als de gebruiker de view_role permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $role = Mockery::mock(Role::class);
    $user->shouldReceive('can')->once()->with('view_role')->andReturn(false);

    $result = $this->policy->view($user, $role);

    expect($result)->toBeFalse();
});

test('de create methode staat de toegang toe als de gebruiker de create_role permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('create_role')->andReturn(true);

    $result = $this->policy->create($user);

    expect($result)->toBeTrue();
});

test('de create methode weigert de toegang als de gebruiker de create_role permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('create_role')->andReturn(false);

    $result = $this->policy->create($user);

    expect($result)->toBeFalse();
});

test('de update methode staat de toegang toe als de gebruiker de update_role permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $role = Mockery::mock(Role::class);
    $user->shouldReceive('can')->once()->with('update_role')->andReturn(true);

    $result = $this->policy->update($user, $role);

    expect($result)->toBeTrue();
});

test('de update methode weigert de toegang als de gebruiker de update_role permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $role = Mockery::mock(Role::class);
    $user->shouldReceive('can')->once()->with('update_role')->andReturn(false);

    $result = $this->policy->update($user, $role);

    expect($result)->toBeFalse();
});

test('de delete methode staat de toegang toe als de gebruiker de delete_role permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $role = Mockery::mock(Role::class);
    $user->shouldReceive('can')->once()->with('delete_role')->andReturn(true);

    $result = $this->policy->delete($user, $role);

    expect($result)->toBeTrue();
});

test('de delete methode weigert de toegang als de gebruiker de delete_role permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $role = Mockery::mock(Role::class);
    $user->shouldReceive('can')->once()->with('delete_role')->andReturn(false);

    $result = $this->policy->delete($user, $role);

    expect($result)->toBeFalse();
});

test('de deleteAny methode staat de toegang toe als de gebruiker de delete_any_role permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_any_role')->andReturn(true);

    $result = $this->policy->deleteAny($user);

    expect($result)->toBeTrue();
});

test('de deleteAny methode weigert de toegang als de gebruiker de delete_any_role permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_any_role')->andReturn(false);

    $result = $this->policy->deleteAny($user);

    expect($result)->toBeFalse();
});
