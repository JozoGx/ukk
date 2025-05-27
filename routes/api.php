<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruApi;
use App\Http\Controllers\SiswaApi;
use App\Http\Controllers\IndustriApi;
use App\Http\Controllers\PklApi;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('guru', GuruApi::class);
Route::apiResource('siswa', SiswaApi::class);
Route::apiResource('industri', IndustriApi::class);
Route::apiResource('pkl', PklApi::class);
