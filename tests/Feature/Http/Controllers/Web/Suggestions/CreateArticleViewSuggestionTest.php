<?php

use App\Models\User;
use Database\Seeders\PartOfSpeechTableSeeder;
use Database\Seeders\RegionTableSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

it('can successfully render the suggestion view', function (): void {
    seed([PartOfSpeechTableSeeder::class, RegionTableSeeder::class]);
    $user = User::factory()->create();

    get(route('definitions.create'))->assertSuccessful();

    actingAs($user)->get(route('definitions.create'))->assertSuccessful();
});
