<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Articles;

use App\Actions\Articles\StoreArticleSuggestion;
use App\Data\SuggestionData;
use App\Models\Article;
use App\Models\Concept;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;

use function Pest\Laravel\actingAs;

function makeSuggestionData(array $overrides = []): SuggestionData
{
    return new SuggestionData(
        word: $overrides['word'] ?? 'testwoord',
        description: $overrides['description'] ?? 'Een omschrijving.',
        regions: $overrides['regions'] ?? [],
        characteristics: $overrides['characteristics'] ?? null,
        creator_id: $overrides['creator_id'] ?? null,
        part_of_speech_id: $overrides['part_of_speech_id'] ?? null,
        notify_author: $overrides['notify_author'] ?? false,
    );
}

beforeEach(function (): void {
    $this->action = new StoreArticleSuggestion();
});

describe('execute() - happy path', function (): void {
    it('creates an article suggestion and returns it', function (): void {
        actingAs(User::factory()->create());

        $article = $this->action->execute(makeSuggestionData());

        expect($article)->toBeInstanceOf(Article::class);
        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    });

    it('persists the word and description on the article', function (): void {
        actingAs(User::factory()->create());

        $article = $this->action->execute(makeSuggestionData([
            'word' => 'fiets',
            'description' => 'Een tweewieler.',
        ]));

        $this->assertDatabaseHas('articles', ['id' => $article->id,
            'word' => 'fiets',
            'description' => 'Een tweewieler.',
        ]);
    });

    it('sets the authenticated user as author', function (): void {
        $user = User::factory()->create();
        actingAs($user);

        $article = $this->action->execute(makeSuggestionData());

        expect($article->author_id)->toBe($user->id);
    });

    it('stores the article without an author when unauthenticated', function (): void {
        Auth::logout();

        $article = $this->action->execute(makeSuggestionData());

        expect($article->author_id)->toBeNull();
    });

    it('does not store regions as a direct article attribute', function (): void {
        actingAs(User::factory()->create());

        $regions = Region::factory()->count(2)->create();
        $article = $this->action->execute(makeSuggestionData([
            'regions' => $regions->pluck('id')->all(),
        ]));

        expect($article->getAttributes())->not->toHaveKey('regions');
    });
});

describe('execute() - region sync', function (): void {
    it('syncs the provided regions into articles', function (): void {
        actingAs(User::factory()->create());

        $regions = Region::factory()->count(3)->create();

        $article = $this->action->execute(makeSuggestionData(
            ['regions' => $regions->pluck('id')->all()]
        ));

        expect($article->regions)->toHaveCount(3);
        $regions->each(fn ($region) => expect($article->regions->contains($region))->toBeTrue());
    });

    it('creates the article with no regions when regions array is empty', function (): void {
        actingAs(User::factory()->create());
        $article = $this->action->execute(makeSuggestionData(['regions' => []]));

        expect($article->regions)->toBeEmpty();
    });
});

describe('execute() - concept handling', function (): void {
    it('deletes the concept when one is provided', function (): void {
        actingAs(User::factory()->create()); 

        $concept = Concept::factory()->create();

        $this->action->execute(makeSuggestionData(), $concept);
        $this->assertDatabaseMissing('concepts', ['id' => $concept->id]);
    });

    it ('does not fail when no concept is provided', function (): void {
        actingAs(User::factory()->create());
        
        $article = $this->action->execute(makeSuggestionData(), null);

        expect($article)->toBeInstanceOf(Article::class);
    });
});

describe('execute() - flash messages', function (): void {
    it('flashes a message referencing "je account" for authenticated users', function () {
        actingAs(User::factory()->create());
 
        $this->action->execute(makeSuggestionData());

        $message = session('flash_message') ?? session('laravel_flash_message.message') ?? '';
        expect($message)->toContain('Op je account kun je de status opvolgen van elke suggestie die je hebt ingediend.');
    });
 
    it('flashes a message with a register prompt for guests', function () {
        Auth::logout();
 
        $this->action->execute(makeSuggestionData());
 
        $message = session('flash_message') ?? session('laravel_flash_message.message') ?? '';
        expect($message)->toContain('Wil je weten wanneer je suggestie online komt? Registreer je dan als gebruiker');
    });
});

describe('execute() - transactional integrity', function (): void {
    it('rolls back the transaction when region sync fails', function () {
        actingAs(User::factory()->create());
 
        $before = Article::count();
 
        try {
            $this->action->execute(makeSuggestionData(['regions' => [99999]]));
        } catch (Throwable) {
            // expected
        }
 
        expect(Article::count())->toBe($before);
    });
 
    it('does not delete the concept when the transaction fails', function () {
        actingAs(User::factory()->create());
 
        $concept = Concept::factory()->create();
 
        try {
            $this->action->execute(makeSuggestionData(['regions' => [99999]]), $concept);
        } catch (Throwable) {
            // expected
        }
 
        $this->assertDatabaseHas('concepts', ['id' => $concept->id]);
    });
});