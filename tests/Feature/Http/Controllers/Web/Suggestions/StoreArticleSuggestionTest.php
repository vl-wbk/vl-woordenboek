<?php

use App\Models\Article;
use App\Models\PartOfSpeech;
use App\Models\Region;
use App\Models\User;
use Database\Seeders\PartOfSpeechTableSeeder;
use Database\Seeders\RegionTableSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use function Pest\Laravel\seed;

beforeEach(function (): void {
    seed([PartOfSpeechTableSeeder::class, RegionTableSeeder::class]);

    $this->articleData = Article::factory()->make();

    $this->formData = [
        'woord' => $this->articleData->word,
        'beschrijving' => $this->articleData->description,
        'voorbeeld' => $this->articleData->example,
        'regio' => [Region::first()->id],
        'kenmerken' => $this->articleData->characteristics,
        'woordsoort' => PartOfSpeech::first()->id,
    ];
});

it('can store an article suggestion as guest user', function(): void {
    $user = User::factory()->create();

    post(route('definitions.store'), $this->formData)
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->assertDatabaseMissing(Article::class, ['author_id' => $user->id]);
})->group('suggestions', 'articles');

it ('can store an article as authenticated user and the auth identifier is attached', function (): void {
    $user = User::factory()->create();

    actingAs($user)->post(route('definitions.store'), array_merge($this->formData, ['creator' => $user->id]))
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas(Article::class, ['author_id' => $user->id]);
})->group('suggestions', 'articles');

it('test all that the needed fields are registered as required', function (string $input): void {
    post(route('definitions.store'), [])->assertSessionHasErrors($input);
})
->with(['voorbeeld', 'regio', 'woord', 'beschrijving'])
->group('suggestions', 'articles');
