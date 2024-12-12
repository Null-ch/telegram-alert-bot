<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelegramController;

Route::post('/webhook/{prefix}', [TelegramController::class, 'handleWebhook'])->name('telegram_webhook');
Route::get('/webhook/set/{prefix}', [TelegramController::class, 'setWebhook']);
Route::get('/webhook/remove/{prefix}', [TelegramController::class, 'removeWebhook']);
