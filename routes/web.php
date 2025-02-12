<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;


// Auth::routes(['register' => false]);
Route::post('/handle-form', [AdminController::class, 'sendMailing'])->name('send.mailing');