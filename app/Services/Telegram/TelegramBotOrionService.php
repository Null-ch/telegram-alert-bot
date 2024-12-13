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
        $currentChatId = $this->getAdminChatId();

        if ($this->isBusinessMessage($response)) {
            $message = $this->handleBusinessMessage($response, '@HelpdeskOrionTerminal');
        } else {
            if ($this->isPrivate($this->getChatType($response))) {
                $message = $this->handlePersonalMessage([
                    'accountName' => 'HelpDesk Orion-Terminal',
                    'accountTag' => '@HelpdeskOrionTerminal',
                ]);
                $currentChatId = $this->getChatId($response);
            } else {
                $message = $this->handleGrouplMessage($response, '@HelpdeskOrionTerminal');
            }
        }

        if ($message) {
            $this->sendResponse($currentChatId, $message, 'botOrion');
        }
    }
}
