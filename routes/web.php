<?php

use App\Http\Controllers\Shared\Authentication\MyWelcomeController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\WelcomeNotification\WelcomesNewUsers;

/**
 * These welcome routes are not defined with the route attribute system. Because they are mapped to an controller from an external package.
 * And thus not possible to map them with the route attributes system.
 */
Route::group(['middleware' => ['web', WelcomesNewUsers::class]], function (): void {
    Route::get('welkom/{user}', [MyWelcomeController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('welkom/{user}', [MyWelcomeController::class, 'savePassword']);
});

Route::get('google-authenticatie/redirect', [\App\Http\Controllers\Shared\Authentication\GoogleOAuthController::class, 'redirect'])
    ->name('login.google.redirect');

Route::get('google-authenticatie/callback', [\App\Http\Controllers\Shared\Authentication\GoogleOAuthController::class, 'callback']);

Route::feeds();
