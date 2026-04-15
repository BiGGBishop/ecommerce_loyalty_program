<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    
    // Purchase Routes
    Route::post('/purchase', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/history', [PurchaseController::class, 'history'])->name('purchase.history');
});

require __DIR__.'/settings.php';