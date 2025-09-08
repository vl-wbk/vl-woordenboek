<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Models\Label;
use App\Models\User;
use App\Policies\LabelPolicy;

beforeEach(function (): void {
    createPermission('page_Articles');

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('page_Articles');

    $this->label = Label::factory()->create();
    $this->labelPolicy = new LabelPolicy();
});

describe('before', function (): void {
    test('Gebruiker heeft de cluster machtiging en before() return is null om verder te gaan naar de specifieke policy methode.', function (): void {
        expect($this->labelPolicy->before($this->user, 'create'))->toBeNull();
    });

    test('De gebruiker heeft niet de benodigde cluster permissie met gevolg dat de before() function een HTTP 404 als return geeft.', function (): void {
        $this->user->revokePermissionTo('page_Articles');

        $policyCheck = $this->labelPolicy->before($this->user, 'create');

        expect($policyCheck->allowed())->toBeFalse()
            ->and($policyCheck->status())->toBe(404);
    });
});

describe('viewAny', function (): void {
    test('Gebruiker heeft de view_any_label permissie en is hierbij gemachtigd om het label overzicht te bekijken', function (): void {
        createPermission('view_any_label');
        $this->user->givePermissionTo('view_any_label');

        expect($this->labelPolicy->viewAny($this->user))->toBeTrue();
    });

    test('Gebruiker heeft niet de view_any_label permissie en kan hierdoor niet het overzicht bekijken', function (): void {
        expect($this->labelPolicy->viewAny($this->user))->toBeFalse();
    });
});

describe('view', function (): void {
    test('gebruiker met de view_label permissie kan specifieke informatie omtrent een label bekijken', function (): void {
        createPermission('view_label');
        $this->user->givePermissionTo('view_label');

        expect($this->labelPolicy->view($this->user, $this->label))->toBeTrue();
    });

    test('gebruiker met de ontbrekende permissie view_label kan specifieke informatie van een label niet bekijken', function (): void {
        expect($this->labelPolicy->view($this->user, $this->label))->toBeFalse();
    });
});

describe('update', function (): void {
    test('gebruiker met de update_label permissie kan de label gegevens aanpassen in het systeem', function (): void {
        createPermission('update_label');
        $this->user->givePermissionTo('update_label');

        expect($this->labelPolicy->update($this->user, $this->label))->toBeTrue();
    });

    test('Gebruiker met de ontbrekende update_label permissie kan geen label informatie aanpassen in het systeem', function (): void {
        expect($this->labelPolicy->update($this->user, $this->label))->toBeFalse();
    });
});

describe('delete', function (): void {
    test('gebruiker met de delete_label permissie kan een label verwijderen in het systeem', function (): void {
        createPermission('delete_label');
        $this->user->givePermissionTo('delete_label');

        expect($this->labelPolicy->delete($this->user))->toBeTrue();
    });

    test('gebruiker met de ontbrekende delete_label_permissie kan geen label verwijderen in het systeem', function (): void {
        expect($this->labelPolicy->delete($this->user))->toBeFalse();
    });
});

describe('create', function (): void {
    test('gebruiker met de create_label permissie kan een label aanmaken in het systeem', function (): void {
        createPermission('create_label');
        $this->user->givePermissionTo('create_label');

        expect($this->labelPolicy->create($this->user))->toBeTrue();
    });

    test('gebruiker met de ontbrekende create_label permissie kan geen label aanmaken in het systeem', function (): void {
        expect($this->labelPolicy->create($this->user))->toBeFalse();
    });
});

describe('attach', function (): void {
    test('gebruiker met de attach_label permissie kan een label koppelen aan een artikel', function (): void {
        createPermission('attach_label');
        $this->user->givePermissionTo('attach_label');

        expect($this->labelPolicy->attach($this->user))->toBeTrue();
    });

    test('gebruiker met de ontbrekende attach_label permissie kan geen label koppelen aan een artikel', function (): void {
        expect($this->labelPolicy->attach($this->user))->toBeFalse();
    });
});

describe('detach', function (): void {
    test('gebruiker met de detach_label permissie kan labels ontkoppelen van artikelen', function (): void {
        createPermission('detach_label');
        $this->user->givePermissionTo('detach_label');

        expect($this->labelPolicy->detach($this->user, $this->label))->toBeTrue();
    });

    test('gebruiker met de ontbrekende detach_label permissie kan geen labels ontkoppelen van artikelen', function (): void {
        expect($this->labelPolicy->detach($this->user, $this->label))->toBeFalse();
    });
});

describe('deleteAny', function (): void {
    test('een gebruiker met de delete_any_label permissie kan meerdere labels verwijderen', function (): void {
        createPermission('delete_any_label');
        $this->user->givePermissionTo('delete_any_label');

        expect($this->labelPolicy->deleteAny($this->user))->toBeTrue();
    });

    test('gebruiker met de missende delete_any_label permissie kan geen labels verwijderen', function (): void {
        expect($this->labelPolicy->deleteAny($this->user))->toBeFalse();
    });
});
