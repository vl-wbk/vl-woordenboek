<?php

use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\SettingsController;
use App\Http\Controllers\Authentication\MyWelcomeController;
use App\Http\Controllers\Definitions\SubmitNewDefinitionController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use Spatie\WelcomeNotification\WelcomesNewUsers;

Route::view('/','welcome')->name('home');
Route::view('/voorwaarden', 'info.terms')->name('terms-of-service');

// Authentication routes
Route::group(['middleware' => ['web', WelcomesNewUsers::class]], function (): void {
    Route::get('welkom/{user}', [MyWelcomeController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('welkom/{user}', [MyWelcomeController::class, 'savePassword']);
});

Route::group(['prefix' => 'definities'], function (): void {
    Route::view('/regio-informatie', 'region-information')->name('definitions.region-info');
    Route::get('insturen', [SubmitNewDefinitionController::class, 'create'])->name('definitions.create');
    Route::post('insturen', [SubmitNewDefinitionController::class, 'store'])
        ->middleware(ProtectAgainstSpam::class)
        ->name('definitions.store');
});

// Accout routes
Route::get('/profiel/{user}', ProfileController::class)->name('profile');

Route::middleware(['auth'])->group(function (): void {
    Route::get('account-instellingen', SettingsController::class)->name('profile.settings');
});
