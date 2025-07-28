<?php

use App\Models\User;
use App\UserTypes;

use function Pest\Laravel\actingAs;

it('can access the filament panels', function(UserTypes $userType) {
    $user = User::factory()->create(['user_type' => $userType]);
    actingAs($user);

    if ($userType->is(UserTypes::Normal)) {
        expect($user->can('access-backend'))->toBeFalse();
    } else {
        expect($user->can('access-backend'))->toBeTrue();
    }
})->with(UserTypes::cases());
