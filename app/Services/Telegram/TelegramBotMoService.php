<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Common\BaseTelegramService;

class TelegramBotMoService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        $response = Telegram::bot('botMo')->getWebhookUpdates();
        $adminChatId = $this->getAdminChatId();

        if ($this->isBusinessMessage($response)) {
            $message = $this->handleBusinessMessage($response, '@HelpDesk_MO');
        } else {
            if ($this->isPrivate($this->getChatType($response))) {
                $message = $this->handlePersonalMessage([
                    'accountName' => 'Helpdesk Terminal МО',
                    'accountTag' => '@HelpDesk_MO',
                ]);
            } else {
                $message = $this->handleGrouplMessage($response, '@HelpDesk_MO');
            }
        }

        if ($message) {
            $this->sendResponse($adminChatId, $message, 'botMo');
        }
    }
}
