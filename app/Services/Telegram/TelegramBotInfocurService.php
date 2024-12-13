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
        $adminChatId = $this->getAdminChatId();

        if ($this->isBusinessMessage($response)) {
            $message = $this->handleBusinessMessage($response, '@HelpdeskTerminal');
        } else {
            if ($this->isPrivate($this->getChatType($response))) {
                $message = $this->handlePersonalMessage([
                    'accountName' => 'Техподдержка ИнфоЦУР',
                    'accountTag' => '@HelpdeskTerminal',
                ]);
            } else {
                $message = $this->handleGrouplMessage($response, '@HelpdeskTerminal');
            }
        }

        if ($message) {
            $this->sendResponse($adminChatId, $message, 'botInfocur');
        }
    }
}
