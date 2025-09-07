<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Models\Etymology;
use App\Models\User;
use App\Policies\EtymologyPolicy;

beforeEach(function(): void {
	createPermission('page_Articles');
	
	$this->user = User::factory()->create();
	$this->user->givePermissionTo('page_Articles');
	
	$this->etymology = Etymology::factory()->create();
	$this->etymologyPolicy = new EtymologyPolicy();
});

describe('before', function (): void
{
	test('Gebruiker heeft de cluster machtiging en before() return is null om verder te gaan naar de specifieke policy methode.', function (): void {
		expect($this->etymologyPolicy->before($this->user, 'create'))->toBeNull();
	});
	
	test('De gebruiker heeft niet de benodigde cluster permissie met gevolg dat de before() function een HTTP 404 als return geeft.', function (): void {
		$this->user->revokePermissionTo('page_Articles');
		
		$policyCheck = $this->etymologyPolicy->before($this->user, 'create');
		
		expect($policyCheck->allowed())->toBeFalse()
			->and($policyCheck->status())->toBe(404);
	});
});

describe('viewAny', function (): void
{
	test('Gebruiker heeft de view_any_etymology permissie en kan de oplijsting van etymologieën bekijken.', function (): void {
		createPermission('view_any_etymology');
		$this->user->givePermissionTo('view_any_etymology');
		
		expect($this->etymologyPolicy->viewAny($this->user))->toBeTrue();
	});
	
	test('Gebruiker heeft niet de benodigde view_any_etymology permissie en kan hierdoor niet het overzicht van etymologieën bekijken.', function (): void {
		expect($this->etymologyPolicy->viewAny($this->user))->toBeFalse();
	});
});

describe('view', function (): void
{
	test('Gebruiker heeft de view_etymology permissie en kan de gegevens bekijken van een specifieke etymologie', function (): void {
	
	});
	
	test('Gebruiker heeft niet de benodigde view_etymology permissie en kan hierdoor geen specifieke gegevens van een etymologie bekijken', function (): void {
		expect($this->etymologyPolicy->view($this->user, $this->etymology))->toBeFalse();
	});
});
