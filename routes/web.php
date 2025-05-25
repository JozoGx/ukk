<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PklController;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/posts', [PklController::class, 'showPosts'])->name('posts');


