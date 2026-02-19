<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use App\DTO\MessageReactionDTO;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\Common\BaseTelegramService;

class TelegramBotTestService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        try {
            $update = Telegram::bot('test')->getWebhookUpdates();
            $data = $update->toArray();
            // Log::info('Data: ' . json_encode($data));

            if ($this->isReaction($update)) {
                $reactionDTO = new MessageReactionDTO($data);
                $this->handleReaction($reactionDTO, 'test');
                return;
            }
        } catch (\Exception $e) {
            // $error = $e->getMessage();
            // $errorMessage = "Ошибка: $error\n";
            // $this->sendResponse(env('TELEGRAM_ERROR_ALERT_CHAT_ID'), $errorMessage, 'test');
            // Log::error('Message: ' . $error, $e->getTrace());
        }
    }
}
