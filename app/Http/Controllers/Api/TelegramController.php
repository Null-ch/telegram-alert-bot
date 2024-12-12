<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Telegram\TelegramBotMoService;
use App\Services\Telegram\TelegramBotOrionService;
use App\Services\Telegram\TelegramBotInfocurService;

class TelegramController extends Controller
{
    private TelegramBotMoService $telegramBotMoService;
    private TelegramBotInfocurService $telegramBotInfocurService;
    private TelegramBotOrionService $telegramBotOrionService;

    public function __construct(
        TelegramBotMoService $telegramBotMoService,
        TelegramBotInfocurService $telegramBotInfocurService,
        TelegramBotOrionService $telegramBotOrionService
    )
    {
        $this->telegramBotMoService = $telegramBotMoService;
        $this->telegramBotInfocurService = $telegramBotInfocurService;
        $this->telegramBotOrionService = $telegramBotOrionService;
    }
    public function setWebhook(string $prefix): JsonResponse 
    {
        $apiResponse = match ($prefix) {
            'mo' => $this->telegramBotMoService->setWebhook($prefix),
            'infocur' => $this->telegramBotInfocurService->setWebhook($prefix),
            'orion' => $this->telegramBotOrionService->setWebhook($prefix),
        };

        return response()->json(['success' => $apiResponse->success, 'error' => $apiResponse->error], $apiResponse->statusCode);
    }

    public function removeWebhook(string $prefix) 
    {
        $apiResponse = match ($prefix) {
            'mo' => $this->telegramBotMoService->removeWebhook($prefix),
            'infocur' => $this->telegramBotInfocurService->removeWebhook($prefix),
            'orion' => $this->telegramBotOrionService->removeWebhook($prefix),
        };

        return response()->json(['success' => $apiResponse->success, 'error' => $apiResponse->error], $apiResponse->statusCode);
    }

    public function handleWebhook(Request $request, string $prefix): void 
    {
        match ($prefix) {
            'mo' => $this->telegramBotMoService->handleWebhook($request),
            'infocur' => $this->telegramBotInfocurService->handleWebhook($request),
            'orion' => $this->telegramBotOrionService->handleWebhook($request),
        };
    }
}
