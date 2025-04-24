<?php

use App\Models\Article;
use App\Models\PartOfSpeech;
use App\Models\Region;
use Database\Seeders\PartOfSpeechTableSeeder;
use Database\Seeders\RegionTableSeeder;

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
    post(route('definitions.store'), $this->formData)
        ->assertRedirect()
        ->assertSessionHasNoErrors();
});

it ('can store an article as authenticated user and the auth identifier is attached', function (): void {
})->skip();

it('test all that the needed fields are registered as required', function (): void {
})->with(['voorbeeld', 'regio', 'woord', 'beschrijving'])->skip();
