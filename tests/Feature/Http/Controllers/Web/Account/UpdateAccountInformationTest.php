<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

beforeEach(function (): void {
    $this->showSettingsEndpoint = route('profile.settings');
    $this->updateSettingsEndpoint = route('user-profile-information.update');
});

test('Unauthenticated users cannot view the account information settings page', function (): void {
    get($this->showSettingsEndpoint)->assertRedirect(route('login'));
});

test('Authenticated user can view successfully their account information settings', function (): void {
    $user = User::factory()->create();
    actingAs($user)->get($this->showSettingsEndpoint)->assertSuccessful();
});

test('unauthenticated users cannot update account information settings', function (): void {
    $response = put($this->updateSettingsEndpoint, [
        'firstname' => 'firstname',
        'lastname' => 'lastname',
        'email' => 'domain@example.tld',
    ]);

    $response->assertRedirect(route('login'));
});

test('authenticated users can change their account information settings', function (): void {
    $user = User::factory()->create();
    $data = ['firstname' => 'new-firstname', 'lastname' => 'new-lastname', 'email' => 'domain@example.tld'];

    $response = actingAs($user)->put($this->updateSettingsEndpoint, data: $data);

    $response->assertSessionHasNoErrors();
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('users', $data);
});

test('that the first name is required', function (string $field): void {
    $user = User::factory()->create();

    actingAs($user)->put($this->updateSettingsEndpoint, data:  [])
        ->assertSessionHasErrorsIn('updateProfileInformation', $field);
})->with(['email', 'firstname', 'lastname']);

