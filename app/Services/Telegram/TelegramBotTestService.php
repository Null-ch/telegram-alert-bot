<?php

namespace App\Services\Telegram;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\FileUpload\InputFile;
use App\Services\Common\BaseTelegramService;

class TelegramBotTestService extends BaseTelegramService
{
    public function handleWebhook(Request $request): void
    {
        try {
            $response = Telegram::bot('test')->getWebhookUpdates();
            $currentChatId = env('TELEGRAM_ERROR_ALERT_CHAT_ID');
            // if ($this->isPrivate($this->getChatType($response))) {
            //     $message = $this->handlePersonalMessage([
            //         'accountName' => 'bot',
            //         'accountTag' => 'bot',
            //     ]);
            //     $currentChatId = $this->getChatId($response);
            // }
            // $this->handleMessageWithAi($response, 'test');
            // $this->handleMessage($response, '@test');

            // if ($this->isBusinessMessage($response)) {
            //     $text = $this->getText($response);
            //     if ($this->isCommand($text) && $text == '/weather') {
            //         $commandName = $this->getCommand($text);
            //         $message = $this->baseCommandService->handle($commandName);
            //     }
            // } else {
            //     $text = $this->getText($response);
            //     if ($this->isPrivate($this->getChatType($response)) && $text == '/weather') {
            //         // $message = $this->handlePersonalMessage([
            //         //     'accountName' => 'Тестовый аккаунт',
            //         //     'accountTag' => 'Тег тестового аккаунта',
            //         // ]);
            //         $text = $this->getText($response);
            //         if ($this->isCommand($text)) {
            //             $commandName = $this->getCommand($text);
            //             $message = $this->baseCommandService->handle($commandName);
            //         }
            //     } else {
            //         $text = $this->getText($response);
            //         if ($this->isCommand($text) && $text == '/weather') {
            //             $commandName = $this->getCommand($text);
            //             $message = $this->baseCommandService->handle($commandName);
            //         }
            //     }
            // }
            // $chat = Telegram::bot('test')->getChat([
            //     'chat_id' => '395590080'
            // ]);
            // if ($message) {


                // $chatId = '395590080';
                // $imageUrl = 'https://i.pinimg.com/736x/d9/54/14/d95414f345910ef41944715280bce387.jpg';
                
                // // Скачиваем изображение
                // $tempPath = storage_path('app/temp_image.jpg');
                // file_put_contents($tempPath, file_get_contents($imageUrl));
                
                // Telegram::bot('test')->sendPhoto([
                //     'chat_id' => $chatId,
                //     'photo' => InputFile::create($tempPath),
                //     'caption' => 'Вот случайная картинка для тебя! 📸'
                // ]);
                
                // // Удаляем временный файл
                // unlink($tempPath);




            // $this->sendResponse('395590080', json_encode($response), 'test');
            // }

        } catch (\Exception $e) {
            // $error = $e->getMessage();
            // $errorMessage = "Ошибка: $error\n";
            // $this->sendResponse(env('TELEGRAM_ERROR_ALERT_CHAT_ID'), $errorMessage, 'test');
            // Log::error('Message: ' . $error, $e->getTrace());
        }
    }
}
