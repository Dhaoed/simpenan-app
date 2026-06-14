<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PendidikanController;
use App\Http\Controllers\API\UmumController;
use App\Http\Controllers\API\KesehatanController;
use App\Http\Controllers\API\UangDukaController;
use App\Http\Controllers\API\SuratController;
use App\Http\Controllers\API\DispensasiController;
use App\Http\Controllers\API\AhliWarisController;
use App\Http\Controllers\API\AdminController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rute CRUD Arsip
Route::apiResource('pendidikan', PendidikanController::class);
Route::apiResource('umum', UmumController::class);
Route::apiResource('kesehatan', KesehatanController::class);
Route::apiResource('uang_duka', UangDukaController::class);
Route::apiResource('surat', SuratController::class);
Route::apiResource('dispensasi', DispensasiController::class);
Route::apiResource('ahli_waris', AhliWarisController::class);
Route::apiResource('admin', AdminController::class);