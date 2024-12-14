<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Telegram\TelegramBotMoService;
use App\Services\Telegram\TelegramBotTestService;
use App\Services\Telegram\TelegramBotOrionService;
use App\Services\Telegram\TelegramBotInfocurService;

class TelegramController extends Controller
{
    private TelegramBotMoService $telegramBotMoService;
    private TelegramBotInfocurService $telegramBotInfocurService;
    private TelegramBotOrionService $telegramBotOrionService;
    private TelegramBotTestService $telegramBotTestService;

    public function __construct(
        TelegramBotMoService $telegramBotMoService,
        TelegramBotInfocurService $telegramBotInfocurService,
        TelegramBotOrionService $telegramBotOrionService,
        TelegramBotTestService $telegramBotTestService,
    )
    {
        $this->telegramBotMoService = $telegramBotMoService;
        $this->telegramBotInfocurService = $telegramBotInfocurService;
        $this->telegramBotOrionService = $telegramBotOrionService;
        $this->telegramBotTestService = $telegramBotTestService;
    }
    public function setWebhook(string $prefix): JsonResponse 
    {
        $apiResponse = match ($prefix) {
            'mo' => $this->telegramBotMoService->setWebhook($prefix),
            'infocur' => $this->telegramBotInfocurService->setWebhook($prefix),
            'orion' => $this->telegramBotOrionService->setWebhook($prefix),
            'test' => $this->telegramBotTestService->setWebhook($prefix),
        };

        return response()->json(['success' => $apiResponse->success, 'error' => $apiResponse->error], $apiResponse->statusCode);
    }

    public function removeWebhook(string $prefix) 
    {
        $apiResponse = match ($prefix) {
            'mo' => $this->telegramBotMoService->removeWebhook($prefix),
            'infocur' => $this->telegramBotInfocurService->removeWebhook($prefix),
            'orion' => $this->telegramBotOrionService->removeWebhook($prefix),
            'test' => $this->telegramBotTestService->removeWebhook($prefix),
        };

        return response()->json(['success' => $apiResponse->success, 'error' => $apiResponse->error], $apiResponse->statusCode);
    }

    public function handleWebhook(Request $request, string $prefix): void 
    {
        match ($prefix) {
            'mo' => $this->telegramBotMoService->handleWebhook($request),
            'infocur' => $this->telegramBotInfocurService->handleWebhook($request),
            'orion' => $this->telegramBotOrionService->handleWebhook($request),
            'test' => $this->telegramBotTestService->handleWebhook($request),
        };
    }
}
