<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Common\BaseTelegramService;

class TelegramBotTestService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        try {
            $response = Telegram::bot('test')->getWebhookUpdates();
            $currentChatId = env('TELEGRAM_ERROR_ALERT_CHAT_ID');

            if ($this->isBusinessMessage($response)) {
                $message = $this->handleBusinessMessage($response, 'Тег тестового аккаунта');
            } else {
                if ($this->isPrivate($this->getChatType($response))) {
                    $message = $this->handlePersonalMessage([
                        'accountName' => 'Тестовый аккаунт',
                        'accountTag' => 'Тег тестового аккаунта',
                    ]);
                    $currentChatId = $this->getChatId($response);
                } else {
                    $message = $this->handleGrouplMessage($response, 'test');
                }
            }
            $chat = Telegram::bot('test')->getChat([
                'chat_id' => '395590080'
            ]);
            if ($message) {
                $this->handleMessage($response, '@HelpdeskTerminal');
                $this->sendResponse($currentChatId, json_encode($chat), 'test');
            }

        } catch (\Exception $e) {
            $error = $e->getMessage();
            $errorMessage = "Ошибка: $error\n";
            $this->sendResponse(env('TELEGRAM_ERROR_ALERT_CHAT_ID'), $errorMessage, 'test');
            Log::error('Message: ' . $error, $e->getTrace());
        }
    }
}
