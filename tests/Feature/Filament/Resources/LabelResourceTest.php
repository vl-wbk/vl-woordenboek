<?php

use App\Filament\Clusters\Articles\Resources\LabelResource\Pages\ListLabels;
use App\Filament\Clusters\Articles\Resources\LabelResource\Pages\ViewLabel;
use App\Models\Label;
use Filament\Tables\Actions\DeleteBulkAction;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    actingAsDeveloper();
});

it ('can render the index page', function(): void {
    livewire(ListLabels::class)->assertSuccessful();
})->group('labels');

it ('can render the information page', function (): void {
    $record = Label::factory()->create();

    livewire(ViewLabel::class, ['record' => $record->getRouteKey()])->assertSuccessful();
})->group('labels');

it('has columns', function (string $column): void {
    livewire(ListLabels::class)->assertTableColumnExists($column);
})
->with(['name', 'articles_count', 'description', 'created_at'])
->group('labels');

it ('can render columns', function (string $column): void {
    livewire(ListLabels::class)->assertCanRenderTableColumn($column);
})
->with(['name', 'articles_count', 'description', 'created_at'])
->group('labels');

it ('can search columns', function (string $column): void {
    $records = Label::factory(5)->create();
    $value = $records->first()->{$column};

    livewire(ListLabels::class)
        ->searchTable($value)
        ->assertCanSeeTableRecords($records->where($column, $value))
        ->assertCanNotSeeTableRecords($records->where($column, '!=', $value));
})
->with(['name'])
->group('labels');

it ('can sort columns', function (string $column): void {
    $records = Label::factory(5)->create();

    livewire(ListLabels::class)
        ->sortTable($column)
        ->assertCanSeeTableRecords($records->sortBy($column), inOrder: true)
        ->sortTable($column, 'desc')
        ->assertCanSeeTableRecords($records->sortByDesc($column), inOrder: true);
})
->with(['articles_count', 'name', 'created_at'])
->group('labels');

it ('can create a record', function (): void {
    livewire(ListLabels::class)
        ->assertActionExists('create')
        ->callAction('create')
        ->assertActionMounted('create');
})->group('labels');

it ('can update a record', function (): void {
    $record = Label::factory()->create();

    livewire(ViewLabel::class, ['record' => $record->getRouteKey()])
        ->assertActionExists('edit')
        ->callAction('edit');
})->group('labels');

it ('can delete a record', function (): void {
    $record = Label::factory()->create();

    livewire(ViewLabel::class, ['record' => $record->getRouteKey()])
        ->assertActionExists('delete')
        ->callAction('delete');

    $this->assertModelMissing($record);
})->group('labels');


it ('can bulk delete records', function (): void {
    $records = Label::factory(5)->create();

    livewire(ListLabels::class)
        ->assertTableBulkActionExists('delete')
        ->callTableBulkAction(DeleteBulkAction::class, $records);

        collect($records)->each(function (Label $label): void {
            $this->assertModelMissing($label);
        });
})->group('labels');
