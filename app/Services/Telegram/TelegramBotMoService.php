<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use App\DTO\MessageReactionDTO;
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
            // $this->handleMessage($response, '@HelpdeskTerminal'); //деактивировано за ненадобностью в текущем проекте

            if ($this->isReaction($response)) {
                $data = $response->toArray();
                $reactionDTO = new MessageReactionDTO($data);
                $this->handleReaction($reactionDTO, 'test');
                return;
            }

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
            // $error = $e->getMessage();
            // $errorMessage = "Ошибка: $error\n";
            // $this->sendResponse(env('TELEGRAM_ERROR_ALERT_CHAT_ID'), $errorMessage, 'test');
            // Log::error('Message: ' . $error, $e->getTrace());
        }
    }
}
