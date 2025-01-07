<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
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
                    $message = $this->handleGrouplMessage($response, 'Тег тестового аккаунта');
                }
            }

            if ($message) {
                $this->sendResponse($currentChatId, env('TELEGRAM_APPEAL_GROUP_ID'), 'test');
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $errorMessage = "Ошибка: $error\n";
            $this->sendResponse(env('TELEGRAM_ERROR_ALERT_CHAT_ID'), $errorMessage, 'test');
        }
    }
}
