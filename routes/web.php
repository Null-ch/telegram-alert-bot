<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;


// Auth::routes(['register' => false]);
Route::get('/', [AdminController::class, 'index'])->name('index');
Route::post('/handle-form', [AdminController::class, 'sendMailing'])->name('send.mailing');
Route::post('/reports/generate', [AdminController::class, 'generateReport'])->name('reports.generate');
Route::get('/reports/download/{id}', [AdminController::class, 'download'])->name('reports.download');
Route::post('/test', [AdminController::class, 'test'])->name('test.test');
