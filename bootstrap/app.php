<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;
use App\Services\Common\BaseTelegramService;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e) {
            $chatId = env('TELEGRAM_ERROR_ALERT_CHAT_ID');
            $error = $e->getMessage();
            $errorMessage = "Ошибка: {$error}\n";

            Log::error("Message: {$error}", $e->getTrace());

            try {
                $telegramService = app(BaseTelegramService::class);
                $telegramService->sendMessage($chatId, $errorMessage, 'test');
            } catch (\Exception $telegramException) {
                Log::error("Ошибка при отправке сообщения об ошибке в Telegram: " . $telegramException->getMessage());
            }
        });
    })->create();
