<?php

use App\Http\Controllers\Authentication\MyWelcomeController;
use Illuminate\Support\Facades\Route;
use Spatie\WelcomeNotification\WelcomesNewUsers;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::group(['middleware' => ['web', WelcomesNewUsers::class]], function (): void {
    Route::get('welkom/{user}', [MyWelcomeController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('welkom/{user}', [MyWelcomeController::class, 'savePassword']);
});
