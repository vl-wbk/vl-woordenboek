<?php

use App\Http\Controllers\Authentication\MyWelcomeController;
use App\Http\Controllers\Definitions\SubmitNewDefinitionController;
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

Route::group(['prefix' => 'definities'], function (): void {
    Route::get('insturen', [SubmitNewDefinitionController::class, 'create'])->name('definitions.create');
});
