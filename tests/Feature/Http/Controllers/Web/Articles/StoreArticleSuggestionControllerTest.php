<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Web\Articles;

use App\Models\PartOfSpeech;
use App\Models\Region;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\RateLimiter;;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
    RateLimiter::clear('suggestion:' . request()->ip());
});
 
// -----------------------------------------------------------------------------
// GET definitions.create
// -----------------------------------------------------------------------------
 
describe('GET definitions.create', function () {
    it('renders the create view', function () {
        $this->get(route('definitions.create'))
            ->assertOk()
            ->assertViewIs('definitions.create');
    });
 
    it('passes all regions to the view', function () {
        $regions = Region::factory()->count(3)->create();
 
        $this->get(route('definitions.create'))
            ->assertViewHas('regions', fn ($viewRegions) =>
                $regions->every(fn ($r) => $viewRegions->has($r->id))
            );
    });
 
    it('passes only suggestible parts of speech to the view', function () {
        $suggestible    = PartOfSpeech::factory()->create(['suggestible' => true]);
        $nonSuggestible = PartOfSpeech::factory()->create(['suggestible' => false]);
 
        $this->get(route('definitions.create'))
            ->assertViewHas('partOfSpeeches', fn ($pos) =>
                $pos->has($suggestible->id) && ! $pos->has($nonSuggestible->id)
            );
    });
 
    it('is accessible to guests', function () {
        $this->get(route('definitions.create'))->assertOk();
    });
});
 
// -----------------------------------------------------------------------------
// POST definitions.store — validation
// -----------------------------------------------------------------------------
 
describe('POST definitions.store validation', function () {
    it('fails when woord is missing', function () {
        $this->from(route('definitions.create'))
            ->post(route('definitions.store'), validPayload(['woord' => '']))
            ->assertSessionHasErrors('woord');
    });
 
    it('fails when woord exceeds 255 characters', function () {
        $this->from(route('definitions.create'))
            ->post(route('definitions.store'), validPayload(['woord' => str_repeat('a', 256)]))
            ->assertSessionHasErrors('woord');
    });
 
    it('fails when beschrijving is missing', function () {
        $this->from(route('definitions.create'))
            ->post(route('definitions.store'), validPayload(['beschrijving' => '']))
            ->assertSessionHasErrors('beschrijving');
    });
 
    it('fails when regio is missing', function () {
        $this->from(route('definitions.create'))
            ->post(route('definitions.store'), validPayload(['regio' => []]))
            ->assertSessionHasErrors('regio');
    });
 
    it('fails when regio is not an array', function () {
        $this->from(route('definitions.create'))
            ->post(route('definitions.store'), validPayload(['regio' => 'not-an-array']))
            ->assertSessionHasErrors('regio');
    });
 
    it('accepts a valid payload and redirects', function () {
        $region = Region::factory()->create();
 
        $this->from(route('definitions.create'))
            ->post(route('definitions.store'), validPayload(['regio' => [$region->id]]))
            ->assertRedirect(route('definitions.create'))
            ->assertSessionHasNoErrors();
    });
});
 
// -----------------------------------------------------------------------------
// POST definitions.store — happy path
// -----------------------------------------------------------------------------
 
describe('POST definitions.store', function () {
    it('redirects to definitions.create after a successful submission', function () {
        $region = Region::factory()->create();
 
        $this->post(route('definitions.store'), validPayload(['regio' => [$region->id]]))
            ->assertRedirect(route('definitions.create'));
    });
 
    it('creates an article record in the database', function () {
        $region = Region::factory()->create();
 
        $this->post(route('definitions.store'), validPayload([
            'woord'        => 'fiets',
            'beschrijving' => 'Een tweewieler.',
            'regio'        => [$region->id],
        ]));
 
        $this->assertDatabaseHas('articles', [
            'word'        => 'fiets',
            'description' => 'Een tweewieler.',
        ]);
    });
 
    it('stores the authenticated user as author', function () {
        $user   = User::factory()->create();
        $region = Region::factory()->create();
 
        $this->actingAs($user)
            ->post(route('definitions.store'), validPayload(['regio' => [$region->id]]));
 
        $this->assertDatabaseHas('articles', ['author_id' => $user->id]);
    });
 
    it('stores the article without an author for guests', function () {
        $region = Region::factory()->create();
 
        $this->post(route('definitions.store'), validPayload(['regio' => [$region->id]]));
 
        $this->assertDatabaseHas('articles', ['author_id' => null]);
    });
 
    it('syncs the submitted regions onto the article', function () {
        $regions = Region::factory()->count(2)->create();
 
        $this->post(route('definitions.store'), validPayload([
            'regio' => $regions->pluck('id')->all(),
        ]));
 
        $article = Article::latest()->first();
 
        expect($article->regions)->toHaveCount(2);
        $regions->each(fn ($r) => expect($article->regions->contains($r))->toBeTrue());
    });
});
 
// -----------------------------------------------------------------------------
// POST definitions.store — rate limiting
// -----------------------------------------------------------------------------
 
describe('POST definitions.store rate limiting', function () {
    it('allows guests up to 4 submissions within the decay window', function () {
        $region = Region::factory()->create();
 
        foreach (range(1, 4) as $i) {
            $this->post(route('definitions.store'), validPayload(['regio' => [$region->id]]))
                ->assertRedirect(route('definitions.create'));
        }
    });
 
    it('blocks guests after 4 submissions with a rate_limit validation error', function () {
        $region = Region::factory()->create();
        $url    = route('definitions.store');
 
        foreach (range(1, 4) as $i) {
            $this->from($url)->post($url, validPayload(['regio' => [$region->id]]));
        }
 
        $this->from($url)
            ->post($url, validPayload(['regio' => [$region->id]]))
            ->assertSessionHasErrors('rate_limit');
    });
 
    it('allows authenticated users up to 12 submissions within the decay window', function () {
        $user   = User::factory()->create();
        $region = Region::factory()->create();
 
        RateLimiter::clear('suggestion:' . $user->getAuthIdentifier());
        $this->actingAs($user);
 
        foreach (range(1, 12) as $i) {
            $this->post(route('definitions.store'), validPayload(['regio' => [$region->id]]))
                ->assertRedirect(route('definitions.create'));
        }
    });
 
    it('blocks authenticated users after 12 submissions with a rate_limit validation error', function () {
        $user   = User::factory()->create();
        $region = Region::factory()->create();
        $url    = route('definitions.store');
 
        RateLimiter::clear('suggestion:' . $user->getAuthIdentifier());
        $this->actingAs($user);
 
        foreach (range(1, 12) as $i) {
            $this->from($url)->post($url, validPayload(['regio' => [$region->id]]));
        }
 
        $this->from($url)
            ->post($url, validPayload(['regio' => [$region->id]]))
            ->assertSessionHasErrors('rate_limit');
    });
 
    it('does not create an article when the rate limit is exceeded', function () {
        $region = Region::factory()->create();
        $url    = route('definitions.store');
 
        foreach (range(1, 4) as $i) {
            $this->from($url)->post($url, validPayload(['regio' => [$region->id]]));
        }
 
        $countBefore = Article::count();
 
        $this->from($url)->post($url, validPayload(['regio' => [$region->id]]));
 
        expect(Article::count())->toBe($countBefore);
    });
});
 
// -----------------------------------------------------------------------------
// Helper
// -----------------------------------------------------------------------------
 
function validPayload(array $overrides = []): array
{
    return array_merge([
        '_token' => csrf_token(),
        'woord'        => 'testwoord',
        'beschrijving' => 'Een omschrijving.',
        'regio'        => [],
    ], $overrides);
}