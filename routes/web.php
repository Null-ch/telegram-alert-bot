<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Web\AdminController::class, 'index'])->middleware('auth');
Auth::routes(['register' => false]);
