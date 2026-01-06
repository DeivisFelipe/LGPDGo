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

    // Cookies
    Route::resource('cookies', App\Http\Controllers\CookieController::class);
    Route::post('/cookies-scan', [App\Http\Controllers\CookieController::class, 'scan'])
        ->name('cookies.scan');
    Route::get('/cookies-export', [App\Http\Controllers\CookieController::class, 'export'])
        ->name('cookies.export');

    // DSAR (Data Subject Access Requests) - Admin
    Route::resource('requests', App\Http\Controllers\RequestController::class)
        ->except(['create', 'store']);

    // Documents Generator
    Route::get('/documents', [App\Http\Controllers\DocumentController::class, 'index'])
        ->name('documents.index');
    Route::get('/documents/privacy-policy', [App\Http\Controllers\DocumentController::class, 'privacyPolicy'])
        ->name('documents.privacy-policy');
    Route::get('/documents/consent-terms', [App\Http\Controllers\DocumentController::class, 'consentTerms'])
        ->name('documents.consent-terms');
    Route::get('/documents/cookie-policy', [App\Http\Controllers\DocumentController::class, 'cookiePolicy'])
        ->name('documents.cookie-policy');
    Route::get('/documents/ropa-report', [App\Http\Controllers\DocumentController::class, 'ropaReport'])
        ->name('documents.ropa-report');
    Route::get('/documents/compliance-report', [App\Http\Controllers\DocumentController::class, 'complianceReport'])
        ->name('documents.compliance-report');
    Route::get('/documents/dsar-report', [App\Http\Controllers\DocumentController::class, 'dsarReport'])
        ->name('documents.dsar-report');

    // Compliance Badge
    Route::get('/compliance-badge', function () {
        $user = auth()->user();
        $company = $user->company;
        $complianceService = app(App\Services\ComplianceScoreService::class);
        $compliance = $complianceService->calculateScore($company);

        return Inertia::render('ComplianceBadge/Index', [
            'company' => $company,
            'compliance' => $compliance,
        ]);
    })->name('compliance-badge.index');
});

// Public DSAR Portal (sem autenticação)
Route::get('/dsar', [App\Http\Controllers\RequestController::class, 'create'])
    ->name('dsar.create');
Route::post('/dsar', [App\Http\Controllers\RequestController::class, 'store'])
    ->name('dsar.store');

// Public Compliance Badge API (sem autenticação)
Route::get('/api/compliance-badge/{slug}', [App\Http\Controllers\ComplianceBadgeController::class, 'badge'])
    ->name('api.compliance-badge');
Route::get('/api/compliance-widget/{slug}', [App\Http\Controllers\ComplianceBadgeController::class, 'widget'])
    ->name('api.compliance-widget');
Route::get('/api/compliance-json/{slug}', [App\Http\Controllers\ComplianceBadgeController::class, 'json'])
    ->name('api.compliance-json');

require __DIR__.'/auth.php';
