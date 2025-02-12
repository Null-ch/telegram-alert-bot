<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Common\BaseTelegramService;

class TelegramBotInfocurService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        try {
            $message = null;
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
                $this->handleMessage($response, '@HelpdeskTerminal');
                $this->sendResponse($currentChatId, $message, 'botInfocur');
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $errorMessage = "Ошибка: $error\n";
            $this->sendResponse(env('TELEGRAM_ERROR_ALERT_CHAT_ID'), $errorMessage, 'test');
            Log::error('Message: ' . $error, $e->getTrace());
        }
    }
}
