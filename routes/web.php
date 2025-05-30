<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardPklController;
use App\Http\Controllers\IndustriController;

Route::get('/', function () {
    return view('welcome');
});

// Middleware: auth, session, verified
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Redirect dashboard ke halaman daftar PKL
    Route::get('/dashboard', [DashboardPklController::class, 'index'])->name('dashboard');

    // PKL Dashboard Routes
    Route::prefix('dashboard/pkls')->name('dashboard.pkls.')->group(function () {
        Route::get('/', [DashboardPklController::class, 'index'])->name('index');
        Route::get('/create', [DashboardPklController::class, 'create'])->name('create');
        Route::post('/', [DashboardPklController::class, 'store'])->name('store');
        Route::get('/{pkl}', [DashboardPklController::class, 'show'])->name('show');
        Route::get('/{pkl}/edit', [DashboardPklController::class, 'edit'])->name('edit');
        Route::put('/{pkl}', [DashboardPklController::class, 'update'])->name('update');
        Route::delete('/{pkl}', [DashboardPklController::class, 'destroy'])->name('destroy');
    });
    Route::resource('industri', IndustriController::class);
});
