<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use App\Services\Common\BaseTelegramService;

class TelegramBotOrionService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
    }
}
