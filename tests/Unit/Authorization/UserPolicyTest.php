<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Response;
use Mockery;

beforeEach(function (): void {
    $this->policy = new UserPolicy();
});

test('de before methode weigert toegang voor gebruikers zonder de page_UserManagement permissie', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('cannot')
        ->once()
        ->with('page_UserManagement')
        ->andReturn(true);

    $response = $this->policy->before($user, 'viewAny');

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->status())->toBe(404);
});

test('de before methode staat toegang toe voor gebruikers met de page_UserManagement permissie', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('cannot')
        ->once()
        ->with('page_UserManagement')
        ->andReturn(false);

    $response = $this->policy->before($user, 'viewAny');

    expect($response)->toBeNull();
});

test('de viewAny methode staat toegang toe voor gebruikers met de view_any_user permissie', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')
        ->once()
        ->with('view_any_user')
        ->andReturn(true);

    expect($this->policy->viewAny($user))->toBeTrue();
});

test('de viewAny methode weigert toegang voor gebruikers zonder de view_any_user permissie', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')
        ->once()
        ->with('view_any_user')
        ->andReturn(false);

    expect($this->policy->viewAny($user))->toBeFalse();
});

test('de create methode staat toegang toe voor gebruikers met de create_user permissie', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')
        ->once()
        ->with('create_user')
        ->andReturn(true);

    expect($this->policy->create($user))->toBeTrue();
});

test('de create methode weigert toegang voor gebruikers zonder de create_user permissie', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')
        ->once()
        ->with('create_user')
        ->andReturn(false);

    expect($this->policy->create($user))->toBeFalse();
});

test('de deactivate methode staat de deactivering van een andere, niet-verbannen gebruiker toe', function (): void {
    $user = Mockery::mock(User::class);
    $model = Mockery::mock(User::class);

    $user->shouldReceive('can')
        ->once()
        ->with('deactivate_user')
        ->andReturn(true);
    $user->shouldReceive('isNot')
        ->once()
        ->with($model)
        ->andReturn(true);
    $model->shouldReceive('isNotBanned')
        ->once()
        ->andReturn(true);

    expect($this->policy->deactivate($user, $model))->toBeTrue();
});

test('de deactivate methode weigert deactivering als de gebruiker de permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $model = Mockery::mock(User::class);

    $user->shouldReceive('can')
        ->once()
        ->with('deactivate_user')
        ->andReturn(false);

    expect($this->policy->deactivate($user, $model))->toBeFalse();
});

test('de deactivate methode weigert zelf deactivering', function (): void {
    $user = Mockery::mock(User::class);
    $model = $user;

    $user->shouldReceive('can')
        ->once()
        ->with('deactivate_user')
        ->andReturn(true);
    $user->shouldReceive('isNot')
        ->once()
        ->with($model)
        ->andReturn(false);

    expect($this->policy->deactivate($user, $model))->toBeFalse();
});

test('de deactivate methode weigert de deactivering van een verbannen gebruiker', function (): void {
    $user = Mockery::mock(User::class);
    $model = Mockery::mock(User::class);

    $user->shouldReceive('can')
        ->once()
        ->with('deactivate_user')
        ->andReturn(true);
    $user->shouldReceive('isNot')
        ->once()
        ->with($model)
        ->andReturn(true);
    $model->shouldReceive('isNotBanned')
        ->once()
        ->andReturn(false);

    expect($this->policy->deactivate($user, $model))->toBeFalse();
});

test('de reactivate methode staat reactivering van een andere, verbannen gebruiker toe', function (): void {
    $user = Mockery::mock(User::class);
    $model = Mockery::mock(User::class);

    $user->shouldReceive('can')
        ->once()
        ->with('reactivate_user')
        ->andReturn(true);
    $user->shouldReceive('isNot')
        ->once()
        ->with($model)
        ->andReturn(true);
    $model->shouldReceive('isBanned')
        ->once()
        ->andReturn(true);

    expect($this->policy->reactivate($user, $model))->toBeTrue();
});

test('de reactivate methode weigert reactivering als de gebruiker de permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $model = Mockery::mock(User::class);

    $user->shouldReceive('can')
        ->once()
        ->with('reactivate_user')
        ->andReturn(false);

    expect($this->policy->reactivate($user, $model))->toBeFalse();
});

test('de reactivate methode weigert zelf-reactivering', function (): void {
    $user = Mockery::mock(User::class);
    $model = $user;

    $user->shouldReceive('can')
        ->once()
        ->with('reactivate_user')
        ->andReturn(true);
    $user->shouldReceive('isNot')
        ->once()
        ->with($model)
        ->andReturn(false);

    expect($this->policy->reactivate($user, $model))->toBeFalse();
});

test('de reactivate methode weigert reactivering van een niet-verbannen gebruiker', function (): void {
    $user = Mockery::mock(User::class);
    $model = Mockery::mock(User::class);

    $user->shouldReceive('can')
        ->once()
        ->with('reactivate_user')
        ->andReturn(true);
    $user->shouldReceive('isNot')
        ->once()
        ->with($model)
        ->andReturn(true);
    $model->shouldReceive('isBanned')
        ->once()
        ->andReturn(false);

    expect($this->policy->reactivate($user, $model))->toBeFalse();
});

test('de updateDeactivation methode staat de wijziging van een andere, verbannen gebruiker toe', function (): void {
    $user = Mockery::mock(User::class);
    $model = Mockery::mock(User::class);

    $user->shouldReceive('can')
        ->once()
        ->with('deactivate_update_user')
        ->andReturn(true);
    $user->shouldReceive('isNot')
        ->once()
        ->with($model)
        ->andReturn(true);
    $model->shouldReceive('isBanned')
        ->once()
        ->andReturn(true);

    expect($this->policy->updateDeactivation($user, $model))->toBeTrue();
});

test('de updateDeactivation methode weigert wijziging als de gebruiker de permissie mist', function (): void {
    $user = Mockery::mock(User::class);
    $model = Mockery::mock(User::class);

    $user->shouldReceive('can')
        ->once()
        ->with('deactivate_update_user')
        ->andReturn(false);

    expect($this->policy->updateDeactivation($user, $model))->toBeFalse();
});

test('de updateDeactivation methode weigert een wijziging van de gebruiker in kwestie', function (): void {
    $user = Mockery::mock(User::class);
    $model = $user;

    $user->shouldReceive('can')
        ->once()
        ->with('deactivate_update_user')
        ->andReturn(true);
    $user->shouldReceive('isNot')
        ->once()
        ->with($model)
        ->andReturn(false);

    expect($this->policy->updateDeactivation($user, $model))->toBeFalse();
});

test('De updateDeactivation methode weigert de wijziging van een niet verbannen gebruiker', function (): void {
    $user = Mockery::mock(User::class);
    $model = Mockery::mock(User::class);

    $user->shouldReceive('can')
        ->once()
        ->with('deactivate_update_user')
        ->andReturn(true);
    $user->shouldReceive('isNot')
        ->once()
        ->with($model)
        ->andReturn(true);
    $model->shouldReceive('isBanned')
        ->once()
        ->andReturn(false);

    expect($this->policy->updateDeactivation($user, $model))->toBeFalse();
});
