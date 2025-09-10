<?php
	
declare(strict_types=1);
	
namespace Tests\Unit\Authorization;
	
use Mockery;
use App\Models\User;
use App\Policies\ExportPolicy;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function (): void {
	$this->policy = new ExportPolicy();
});

test('de view methode staat het bekijken toe als de gebruiker de eigenaar is', function (): void {
	$user = Mockery::mock(User::class);
	$export = Mockery::mock(Export::class);
	$relationship = Mockery::mock(BelongsTo::class);
	
	$export->shouldReceive('user')->andReturn($relationship);
	$relationship->shouldReceive('is')->with($user)->andReturn(true);
	
	$result = $this->policy->view($user, $export);

	expect($result)->toBeTrue();
});

test('de view methode weigert het bekijken als de gebruiker niet de eigenaar is', function (): void {
	$user = Mockery::mock(User::class);
	$export = Mockery::mock(Export::class);
	$relationship = Mockery::mock(BelongsTo::class);
	
	$export->shouldReceive('user')->andReturn($relationship);
	$relationship->shouldReceive('is')->with($user)->andReturn(false);
 
	$result = $this->policy->view($user, $export);
 
	expect($result)->toBeFalse();
});

test('de create methode staat de toegang toe als de gebruiker de export_article permissie heeft', function (): void {
	$user = Mockery::mock(User::class);
	$user->shouldReceive('can')->once()->with('export_article')->andReturn(true);
 
	$result = $this->policy->create($user);
 
	expect($result)->toBeTrue();
});

test('de create methode weigert de toegang als de gebruiker de export_article permissie niet heeft', function (): void {
	$user = Mockery::mock(User::class);
	$user->shouldReceive('can')->once()->with('export_article')->andReturn(false);
 
	$result = $this->policy->create($user);
 
	expect($result)->toBeFalse();
});