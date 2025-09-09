<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use App\Models\Note;
use App\Models\User;
use App\Policies\NotePolicy;

beforeEach(function (): void {
    $this->policy = new NotePolicy();
});

describe('before', function (): void {
    test('Een administrator of ontwikkelaar hebben de volledige toegang tot de notitie functionaliteiten', function (string $role): void {
        $user = User::factory()->{$role}()->create();
        expect($this->policy->before($user, 'create'))->toBeTrue();
    })->with(['administrator', 'developer']);

    test('Een Redacteur en Eindredacteur worden doorverwezen naar specifieke policy classes', function (string $role): void {
        $user = User::factory()->{$role}()->create();
        expect($this->policy->before($user, 'create'))->toBeNull();
    })->with(['editor', 'editorInChief']);
});

describe('update', function (): void {
    test('Een redacteur of eindredacteur kunnen alleen maar notities aanpassen waarvan zij de auteur zijn', function (): void {
        $user = User::factory()->editor()->create();
        $note = Note::factory()->for($user, 'author')->create();

        expect($this->policy->update($user, $note))->toBeTrue();
    });

    test('Een redacteur of eindredacteur kunnen notities van andere auteurs niet aanpassen', function (): void {
        $user = User::factory()->editor()->create();
        $author = User::factory()->create();
        $note = Note::factory()->for($author, 'author')->create();

        expect($this->policy->update($user, $note))->toBeFalse();
    });
});

describe('delete', function (): void {
    test('Een redacteur of eindredeacteur kan alleen maar notifies verwijderen waarvan zij de auteur zijn', function (): void {
        $user = User::factory()->editor()->create();
        $note = Note::factory()->for($user, 'author')->create();

        expect($this->policy->delete($user, $note))->toBeTrue();
    });

    test('Een redacteur of eindredacteur kan geen notities verwijderen van andere auteurs', function (): void {
        $user = User::factory()->editor()->create();
        $author = User::factory()->create();
        $note = Note::factory()->for($author, 'author')->create();

        expect($this->policy->delete($user, $note))->toBeFalse();
    });
});
