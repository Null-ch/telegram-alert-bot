<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Common\BaseTelegramService;

class TelegramBotMoService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        try {
            $message = null;
            $response = Telegram::bot('botMo')->getWebhookUpdates();
            $currentChatId = $this->getAdminChatId();

            if ($this->isBusinessMessage($response)) {
                $message = $this->handleBusinessMessage($response, '@HelpDesk_MO');
            } else {
                if ($this->isPrivate($this->getChatType($response))) {
                    $message = $this->handlePersonalMessage([
                        'accountName' => 'Helpdesk Terminal МО',
                        'accountTag' => '@HelpDesk_MO',
                    ]);
                    $currentChatId = $this->getChatId($response);
                } else {
                    $message = $this->handleGrouplMessage($response, '@HelpDesk_MO');
                }
            }

            if ($message) {
                $this->sendResponse($currentChatId, $message, 'botMo');
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error('Message: ' . $error, $e->getTrace());
        }
    }
}
