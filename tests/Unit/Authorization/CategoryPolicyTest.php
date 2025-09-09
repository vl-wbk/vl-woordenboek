<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;

beforeEach(function (): void {
    createPermission('page_Blog');

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('page_Blog');

    $this->category = Category::factory()->create();
    $this->categoryPolicy = new CategoryPolicy();
});

describe('before', function (): void {
    test('Gebruiker heeft de cluster machtiging en before() return is null om verder te gaan naar de specifieke policy methode.', function (): void {
        expect($this->categoryPolicy->before($this->user, 'create'))->toBeNull();
    });

    test('De gebruiker heeft niet de benodigde cluster permissie met gevolg dat de before() function een HTTP 404 als return geeft.', function (): void {
        $this->user->revokePermissionTo('page_Blog');

        $policyCheck = $this->categoryPolicy->before($this->user, 'create');

        expect($policyCheck->allowed())->toBeFalse()
            ->and($policyCheck->status())->toBe(404);
    });
});

describe('viewAny', function (): void {
    test('Gebruiker met de view_any_category permissie kan het overzicht van categorieën bekijken.', function (): void {
        createPermission('view_any_category');
        $this->user->givePermissionTo('view_any_category');

        expect($this->categoryPolicy->viewAny($this->user))->toBeTrue();
    });

    test('Gebruiker met de missende view_any_category kan het overzicht van categorieën niet bekijken', function (): void {
        expect($this->categoryPolicy->viewAny($this->user))->toBeFalse();
    });
});

describe('view', function (): void {
    test('Gebruiker met de view_category kan gegevens raadplegen van een specifieke categorie', function (): void {
        createPermission('view_category');
        $this->user->givePermissionTo('view_category');

        expect($this->categoryPolicy->view($this->user, $this->category))->toBeTrue();
    });

    test('Gebruiker met de ontbrekende view_category permissie kan geen gegevens raadplegen van een category', function (): void {
        expect($this->categoryPolicy->view($this->user, $this->category))->toBeFalse();
    });
});

describe('create', function (): void {
    test('Gebruiker met de create_category permissie kan een categorie aanmaken in het systeem', function (): void {
        createPermission('create_category');
        $this->user->givePermissionTo('create_category');

        expect($this->categoryPolicy->create($this->user))->toBeTrue();

    });

    test('Gebruiker met de missende create_category permissie kan geen categorie aanmaken in het systeem', function (): void {
        expect($this->categoryPolicy->create($this->user))->toBeFalse();
    });
});


describe('update', function (): void {
    test('Gebruiker met de update_category permissie kan gegevens van een category aanpassen in de applicatie', function (): void {
        createPermission('update_category');
        $this->user->givePermissionTo('update_category');

        expect($this->categoryPolicy->update($this->user, $this->category))->toBeTrue();
    });

    test('Gebruiker met de missende update_category kan geen categorie aanpassen in de applicatie.', function (): void {
        expect($this->categoryPolicy->update($this->user, $this->category))->toBeFalse();
    });
});

describe('delete', function (): void {
    test('Gebruiker met de delete_category permissie kan categorieën verwijderen uit de applicatie', function (): void {
        createPermission('delete_category');
        $this->user->givePermissionTo('delete_category');

        expect($this->categoryPolicy->delete($this->user, $this->category))->toBeTrue();
    });

    test('Gebruiker met de missende delete_category category kan geen categorieën verwijderen uit de applicatie', function (): void {
        expect($this->categoryPolicy->delete($this->user, $this->category))->toBeFalse();
    });
});

describe('deleteAny', function (): void {
    test('Gebruiker met de delete_any_category permissie kan categorieën verwijderen uit de applicatie', function (): void {
        createPermission('delete_any_category');
        $this->user->givePermissionTo('delete_any_category');

        expect($this->categoryPolicy->deleteAny($this->user))->toBeTrue();
    });

    test('Gebruiker met de missende delete_any_category kan geen categorieën verwijderen uit het systeem', function (): void {
        expect($this->categoryPolicy->deleteAny($this->user))->toBeFalse();
    });
});
