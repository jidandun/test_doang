<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\OpportunityController as AdminOpportunityController;
use App\Http\Controllers\OpportunityController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OpportunityController::class, 'index'])->name('home');
Route::get('/informasi/{opportunity:slug}', [OpportunityController::class, 'show'])->name('opportunities.show');
Route::get('/informasi/{opportunity:slug}/daftar', [OpportunityController::class, 'redirect'])->name('opportunities.redirect');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.store');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', fn () => redirect()->route('admin.opportunities.index'))->name('dashboard');
        Route::resource('opportunities', AdminOpportunityController::class)->except(['show']);
    });
});
