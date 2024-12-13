<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Common\BaseTelegramService;

class TelegramBotOrionService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        $response = Telegram::bot('botOrion')->getWebhookUpdates();
        $adminChatId = $this->getAdminChatId();

        if ($this->isBusinessMessage($response)) {
            $this->handleBusinessMessage($response, $adminChatId);
        } else {
            if ($this->isPrivate($this->getChatType($response))) {
                $this->handlePersonalMessage($response, []);
            } else {
                $this->handleGrouplMessage($response, $adminChatId);
            }
        }
    }
}
