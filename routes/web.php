<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Onboarding
    Route::post('/onboarding/complete', function () {
        auth()->user()->update(['onboarding_completed' => true]);
        return redirect()->back();
    })->name('onboarding.complete');

    // Data Inventories (ROPA)
    Route::resource('data-inventories', App\Http\Controllers\DataInventoryController::class);
    Route::get('/data-inventories/{dataInventory}/completeness', [App\Http\Controllers\DataInventoryController::class, 'checkCompleteness'])
        ->name('data-inventories.completeness');
    Route::get('/data-inventories-export', [App\Http\Controllers\DataInventoryController::class, 'export'])
        ->name('data-inventories.export');
});

require __DIR__.'/auth.php';
