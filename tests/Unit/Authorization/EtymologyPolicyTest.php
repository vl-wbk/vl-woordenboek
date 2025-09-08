<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Enums\Articles\EtymologyStatus;
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
		createPermission('view_etymology');
		$this->user->givePermissionTo('view_etymology');
		
		expect($this->etymologyPolicy->view($this->user, $this->etymology))->toBeTrue();
	});
	
	test('Gebruiker heeft niet de benodigde view_etymology permissie en kan hierdoor geen specifieke gegevens van een etymologie bekijken', function (): void {
		expect($this->etymologyPolicy->view($this->user, $this->etymology))->toBeFalse();
	});
});

describe('update', function (): void
{
	test('De gebruiker heeft de update_etymology permissie en kan de gegevens van de etymology aanpassen.', function (EtymologyStatus $state): void {
		$etymology = Etymology::factory()->create(['status' => $state]);
		
		createPermission('update_etymology');
		$this->user->givePermissionTo('update_etymology');
		
		expect($this->etymologyPolicy->update($this->user, $etymology))->toBeTrue();
	})->with([EtymologyStatus::Draft]);
	
	test('Een gebruiker met de update_etymology permissie kan de gegevens niet aanpassen, wanneer de status het verhinderd.', function (EtymologyStatus $state): void {
		$etymology = Etymology::factory()->create(['status' => $state]);
		
		createPermission('update_etymology');
		$this->user->givePermissionTo('update_etymology');
		
		expect($this->etymologyPolicy->update($this->user, $etymology))->toBeFalse();
	})->with([EtymologyStatus::UnderReview, EtymologyStatus::Archived, EtymologyStatus::Rejected, EtymologyStatus::Published]);
});

describe('delete', function (): void
{
	test('Gebruiker met de delete_etymology permissie kan een etymologie verwijderen in het systeem.', function (EtymologyStatus $etymologyStatus): void {
		$etymology = Etymology::factory()->create(['status' => $etymologyStatus]);
		
		createPermission('delete_etymology');
		$this->user->givePermissionTo('delete_etymology');
		
		expect($this->etymologyPolicy->delete($this->user, $etymology))->toBeTrue();
	})->with(EtymologyStatus::cases());
	
	test('Gebruiker met de ontbrekende delete_etymology permissie kan geen etymologische gegevens verwijderen uit het systeem', function (): void {
		expect($this->etymologyPolicy->update($this->user, $this->etymology))->toBeFalse();
	});
});

describe('deleteAny', function (): void
{
	test('Gebruiker met de delete_any_etymology permissie kan meerdere etymologieën verwijderen in het systeem.', function (): void {
		createPermission('delete_any_etymology');
		$this->user->givePermissionTo('delete_any_etymology');
		
		expect($this->etymologyPolicy->deleteAny($this->user))->toBeTrue();
	});
	
	test('Gebruiker met de delete_any_etymology permissie kan geen meerdere etymologieen verwijderen in het systeem', function (): void {
		expect($this->etymologyPolicy->deleteAny($this->user))->toBeFalse();
	});
});

describe('reject', function (): void
{
	test('Gebruiker met de reject_etymology permissie kan een etymologische bijdrage weigeren in het systeem', function (EtymologyStatus $etymologyStatus): void {
		createPermission('reject_etymology');
		$this->user->givePermissionTo('reject_etymology');
		
		$etymology = Etymology::factory()->create(['status' => $etymologyStatus]);
		
		expect($this->etymologyPolicy->reject($this->user, $etymology))->toBeTrue();
	})->with([EtymologyStatus::UnderReview]);
	
	test('Gebruiker met de reject_etymology permissie kan geen bijdrage afwijzen wanneer deze niet onder review staat', function (EtymologyStatus $etymologyStatus): void {
		createPermission('reject_etymology');
		$this->user->givePermissionTo('reject_etymology');
		
		$etymology = Etymology::factory()->create(['status' => $etymologyStatus]);
		
		expect($this->etymologyPolicy->reject($this->user, $etymology))->toBeFalse();
	})->with([EtymologyStatus::Draft, EtymologyStatus::Archived, EtymologyStatus::Rejected, EtymologyStatus::Published]);
	
	test('faalt voor de gebruiker zonder reject_etymology permissie', function (): void {
		$etymology = Etymology::factory()->create(['status' => EtymologyStatus::UnderReview]);
		expect($this->etymologyPolicy->reject($this->user, $etymology))->toBeFalse();
	});
});

describe('publish', function (): void
{
	test('Gebruiker met de publish_etymology permissie en de correcte status kan etymologische gegevens publiceren', function (EtymologyStatus $etymologyStatus): void {
		createPermission('publish_etymology');
		$etymology = Etymology::factory()->create(['status' => $etymologyStatus]);
		$this->user->givePermissionTo('publish_etymology');
		
		expect($this->etymologyPolicy->publish($this->user, $etymology))->toBeTrue();
	})->with([EtymologyStatus::UnderReview, EtymologyStatus::Archived]);
	
	test('Gebruiker met de publish_etymology permissie kan geen gegevens publiceren met een incorrecte record status', function (EtymologyStatus $etymologyStatus): void {
		createPermission('publish_etymology');
		$etymology = Etymology::factory()->create(['status' => $etymologyStatus]);
		$this->user->givePermissionTo('publish_etymology');
		
		expect($this->etymologyPolicy->publish($this->user, $etymology))->toBeFalse();
	})->with([EtymologyStatus::Published, EtymologyStatus::Rejected, EtymologyStatus::Draft]);
	
	test('faalt voor de gebruiker zonder reject_etymology permissie', function (): void {
		$etymology = Etymology::factory()->create(['status' => EtymologyStatus::UnderReview]);
		expect($this->etymologyPolicy->publish($this->user, $etymology))->toBeFalse();
	});
});

describe('draft', function (): void
{
	test('Gebruiker met de update_etymology permissie en de correcte status kan etymologische gegevens aanpassen', function (EtymologyStatus $etymologyStatus): void {
		createPermission('update_etymology');
		$etymology = Etymology::factory()->create(['status' => $etymologyStatus]);
		
		$this->user->givePermissionTo('update_etymology');
		
		expect($this->etymologyPolicy->draft($this->user, $etymology))->toBeTrue();
	})->with([EtymologyStatus::UnderReview, EtymologyStatus::Rejected, EtymologyStatus::Archived]);
	
	test('Gebruiker met de update_etymology en incorrecte status kan geen etymologische gegevens aanpassen', function (EtymologyStatus $etymologyStatus): void {
		createPermission('update_etymology');
		$etymology = Etymology::factory()->create(['status' => $etymologyStatus]);
		
		$this->user->givePermissionTo('update_etymology');
		
		expect($this->etymologyPolicy->draft($this->user, $etymology))->toBeFalse();
	})->with([EtymologyStatus::Published, EtymologyStatus::Draft]);
	
	test('faalt voor de gebruiker zonder reject_etymology permissie', function (): void {
		$draft = Etymology::factory()->create(['status' => EtymologyStatus::Rejected]);
		expect($this->etymologyPolicy->draft($this->user, $draft))->toBeFalse();
	});
});

describe('underReview', function (): void
{
	test('Gebruiker met de update_etymology permissie en de correcte status kan de etymologische gegevens onder review plaatsen', function (EtymologyStatus $status): void {
		createPermission('under_review_etymology');
		$this->user->givePermissionTo('under_review_etymology');
		
		$etymology = Etymology::factory()->create(['status' => $status]);
		
		
		expect($this->etymologyPolicy->underReview($this->user, $etymology))->toBeTrue();
	})->with([EtymologyStatus::Draft]);
	
	test('Gebruiker met de update_etymology permissie en de incorrecte status kan de etymologische gegevens niet onder review plaatsen', function (EtymologyStatus $status): void {
		createPermission('under_review_etymology');
		$this->user->givePermissionTo('under_review_etymology');
		
		$etymology = Etymology::factory()->create(['status' => $status]);
		
		
		expect($this->etymologyPolicy->underReview($this->user, $etymology))->toBeFalse();
	})->with([EtymologyStatus::UnderReview, EtymologyStatus::Published, EtymologyStatus::Archived, EtymologyStatus::Rejected]);
	
	test('faalt voor de gebruiker zonder reject_etymology permissie', function (): void {
		expect($this->etymologyPolicy->underReview($this->user, $this->etymology))->toBeFalse();
	});
});