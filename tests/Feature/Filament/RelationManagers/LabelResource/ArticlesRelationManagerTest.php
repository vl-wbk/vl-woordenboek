<?php

use App\Filament\Clusters\Articles\Resources\LabelResource\Pages\ViewLabel;
use App\Filament\Clusters\Articles\Resources\LabelResource\RelationManagers\ArticlesRelationManager;
use App\Models\Article;
use App\Models\Label;

use function Pest\Livewire\livewire;

it('can render the relation manager', function (): void {
    $label = Label::factory()
        ->has(Article::factory()->count(5))
        ->create();

    livewire(ArticlesRelationManager::class, ['ownerRecord' => $label, 'pageClass' => ViewLabel::class])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($label->articles);
})->group('labels');
