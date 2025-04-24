<?php

use App\Filament\Clusters\Articles\Resources\LabelResource\Pages\ListLabels;
use App\Models\Label;

use function Pest\Livewire\livewire;

it ('can render the index page', function(): void {
})->group('labels');

it ('can render the information page', function (): void {
})->group('labels');

it('has columns', function (): void {
})->group('labels');

it ('can render columns', function (): void {
})->group('labels');

it ('can search columns', function (): void {
})->group('labels');

it ('can sort columns', function (): void {
})->group('labels');

it ('can create a record', function (): void {
    actingAsDeveloper();

    livewire(ListLabels::class)
        ->assertActionExists('create')
        ->callAction('create');
})->group('labels');

it ('can update a record', function (): void {

})->group('labels');

it ('can delete a record', function (): void {
})->group('labels');


it ('can bulk delete records', function (): void {
})->group('labels');

it ('can validate required', function (): void {
})->group('labels');


it ('can validate unique', function (): void {
})->group('labels');
