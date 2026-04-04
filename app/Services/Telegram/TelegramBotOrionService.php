<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use App\DTO\MessageReactionDTO;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Common\BaseTelegramService;

class TelegramBotOrionService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        try {
            $message = null;
            $response = Telegram::bot('botOrion')->getWebhookUpdates();
            $currentChatId = $this->getAdminChatId();
            // $this->handleMessage($response, '@HelpdeskTerminal'); //деактивировано за ненадобностью в текущем проекте
            
            data1 = $response->toArray();
            Log::info('Data: ' . json_encode($data1));
            
            if ($this->isReaction($response)) {
                $data = $response->toArray();
                $reactionDTO = new MessageReactionDTO($data);
                $this->handleReaction($reactionDTO, 'OrionTerminal');
            }

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
                $this->sendMessage($currentChatId, $message, 'botOrion');
            }
        } catch (\Exception $e) {
            // $error = $e->getMessage();
            // $errorMessage = "Ошибка: $error\n";
            // $this->sendResponse(env('TELEGRAM_ERROR_ALERT_CHAT_ID'), $errorMessage, 'test');
            // Log::error('Message: ' . $error, $e->getTrace());
        }
    }
}
