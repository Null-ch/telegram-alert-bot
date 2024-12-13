<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Common\BaseTelegramService;

class TelegramBotInfocurService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        $response = Telegram::bot('botInfocur')->getWebhookUpdates();
        $currentChatId = $this->getAdminChatId();

        if ($this->isBusinessMessage($response)) {
            $message = $this->handleBusinessMessage($response, '@HelpdeskTerminal');
        } else {
            if ($this->isPrivate($this->getChatType($response))) {
                $message = $this->handlePersonalMessage([
                    'accountName' => 'Техподдержка ИнфоЦУР',
                    'accountTag' => '@HelpdeskTerminal',
                ]);
                $currentChatId = $this->getChatId($response);
            } else {
                $message = $this->handleGrouplMessage($response, '@HelpdeskTerminal');
            }
        }

        if ($message) {
            $this->sendResponse($currentChatId, $message, 'botInfocur');
        }
    }
}
