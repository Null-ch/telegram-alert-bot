<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Common\BaseTelegramService;

class TelegramBotTestService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        $response = Telegram::bot('test')->getWebhookUpdates();
        $currentChatId = $this->getAdminChatId();

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
                $message = json_encode($response);
                $message = $this->handleGrouplMessage($response, 'Тег тестового аккаунта');
            }
        }

        if ($message) {
            $this->sendResponse($currentChatId, $message, 'test');
        }
    }
}
