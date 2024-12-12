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
        $text = json_encode($response);
        Telegram::sendMessage([
            'chat_id' => '395590080',
            // 'chat_id' => '-1002384608890',
            'text' => "Содержимое сообщения ИНФОЦУР:\n{$text}",
        ]);
    }
}
