<?php

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Models\User;
use App\UserTypes;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    actingAsDeveloper();
});

it('can render the index page', function (): void {
    livewire(ListUsers::class)->assertSuccessful();
});

it('can render the create page', function (): void {
    livewire(CreateUser::class)->assertSuccessful();
});

it('can render the edit page', function (): void {
    $record = User::factory()->create();

    livewire(EditUser::class, ['record' => $record->getRouteKey()])
        ->assertSuccessful();
});

it('has the following table columns', function(string $column): void {
    livewire(ListUsers::class)->assertTableColumnExists($column);
})->with(['name', 'user_type', 'email', 'last_seen_at', 'created_at']);

it ('can render the following table columns', function (string $column): void {
    livewire(ListUsers::class)->assertCanRenderTableColumn($column);
})->with(['name', 'user_type', 'email', 'last_seen_at', 'created_at']);

it ('can sort the following columns', function (string $column): void {
    $records = User::factory(5)->create();

    livewire(ListUsers::class)
        ->sortTable($column)
        ->assertCanSeeTableRecords($records->sortBy($column), inOrder: true)
        ->sortTable($column, 'desc')
        ->assertCanSeeTableRecords($records->sortByDesc($column), inOrder: true);
})->with(['last_seen_at', 'created_at']);

it ('can search the following table columns', function (string $column): void {
    $records = User::factory(5)->create();
    $value = $records->first()->{$column};

    livewire(ListUsers::class)
        ->searchTable($value)
        ->assertCanSeeTableRecords($records->where($column, $value))
        ->assertCanNotSeeTableRecords($records->where($column, '!=', $value));
})->with(['email', 'name']);

it('can create a new record', function (): void {
    $record = User::factory()->make();

    livewire(CreateUser::class)
        ->fillForm([
            'firstname' => $record->firstname,
            'lastname' => $record->lastname,
            'user_type' => UserTypes::Developer,
            'email' => $record->email,
            'password' => $record->password,
            'password_confirmation' => $record->password,
        ])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, ['firstname' => $record->firstname, 'lastname' => $record->lastname, 'email' => $record->email]);
});

it('can update a record', function (): void {
    $record = User::factory()->create();
    $newRecord = User::factory()->make();

    livewire(EditUser::class, ['record' => $record->getRouteKey()])
        ->fillForm(['firstname' => $newRecord->firstname, 'lastname' => $newRecord->lastname, 'email' => $newRecord->email])
        ->assertActionExists('save')
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, ['firstname' => $newRecord->firstname, 'lastname' => $newRecord->lastname, 'email' => $newRecord->email]);
});

it ('can delete a record', function (): void {
    $record = User::factory()->create();

    livewire(EditUser::class, ['record' => $record->getRouteKey()])
        ->assertActionExists('delete')
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($record);
});

it('can bulk delete a record', function (): void {
    $records = User::factory(5)->create();

    livewire(ListUsers::class)
        ->assertTableBulkActionExists('delete')
        ->callTableBulkAction(DeleteBulkAction::class, $records);

    foreach ($records as $record) {
        $this->assertModelMissing($record);
    }
});

it('can validate required', function (string $column) {
    livewire(CreateUser::class)
        ->fillForm([$column => null])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasFormErrors([$column => ['required']]);
})->with(['firstname', 'lastname', 'email']);

it('can validate unique', function (string $column) {
    $record = User::factory()->create();

    livewire(CreateUser::class)
        ->fillForm(['email' => $record->email])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasFormErrors([$column => ['unique']]);
})->with(['email']);

it('can validate email', function (string $column) {
    livewire(CreateUser::class)
        ->fillForm(['email' => Str::random()])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasFormErrors([$column => ['email']]);
})->with(['email']);
