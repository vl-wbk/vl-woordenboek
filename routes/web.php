<?php

use App\Http\Controllers\Shared\Authentication\MyWelcomeController;
use Illuminate\Support\Facades\Route;
use Spatie\WelcomeNotification\WelcomesNewUsers;

/**
 * These welcome routes are not definted with the route attribute system. Because they are mapped to an controller from an external package.
 * And thus not possible to map them with the route attributes system.
 */
Route::group(['middleware' => ['web', WelcomesNewUsers::class]], function (): void {
    Route::get('welkom/{user}', [MyWelcomeController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('welkom/{user}', [MyWelcomeController::class, 'savePassword']);
});
