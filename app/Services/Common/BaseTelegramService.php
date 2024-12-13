<?php

namespace App\Services\Common;

use GuzzleHttp\Client;
use App\DTO\ApiResponseDTO;
use Illuminate\Support\Arr;
use Telegram\Bot\Objects\Update;
use Psr\Http\Message\ResponseInterface;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Interfaces\TelegramServiceInterface;

class BaseTelegramService implements TelegramServiceInterface
{
    public Client $client;
    public function __construct()
    {
        $this->client = new Client;
    }

    public function setWebhook(string $prefix): ApiResponseDTO
    {
        $token = $this->getToken($prefix);
        $telegramApiUrl = $this->getTelegramApiUrl($token, 'set');
        $webhookUrl = $this->getWebhookUrl($prefix);
        $response = $this->client->request('GET', $telegramApiUrl . $webhookUrl);
        return $this->responseProcessing($response);
    }
    public function removeWebhook(string $prefix): ApiResponseDTO
    {
        $token = $this->getToken($prefix);
        $telegramApiUrl = $this->getTelegramApiUrl($token, 'remove');
        $response = $this->client->request('GET', $telegramApiUrl);
        return $this->responseProcessing($response);
    }
    private function getToken(string $prefix): string
    {
        return env('TELEGRAM_' . strtoupper($prefix) . '_BOT_TOKEN');
    }
    private function getTelegramApiUrl(string $token, string $type): ?string
    {
        if ($type == 'set') {
            return 'https://api.telegram.org/bot' . $token . '/setWebhook?url=';
        }
        if ($type == 'remove') {
            return 'https://api.telegram.org/bot' . $token . '/deleteWebhook';
        }

        return null;
    }
    private function getWebhookUrl(string $prefix): string
    {
        return route('telegram_webhook', ['prefix' => $prefix]);
    }
    private function responseProcessing(ResponseInterface $response): ApiResponseDTO
    {
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody(), true);
        if ($statusCode == 200) {
            return new ApiResponseDTO($statusCode, true, $body, null);
        } else {
            $errorMessage = $this->getErrorMessage($statusCode);
            return new ApiResponseDTO($statusCode, false, $body, $errorMessage);
        }
    }
    private function getErrorMessage(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Bad Request: Invalid input.',
            404 => 'Not Found: The requested resource was not found.',
            500 => 'Internal Server Error: The server encountered an unexpected error.',
            default => "API request failed with status code {$statusCode}",
        };
    }
    public function getDefaultCallback(array $params): string
    {
        $channel = Arr::get($params, 'channel');
        $accountName = Arr::get($params, 'accountName');
        $accountTag = Arr::get($params, 'accountTag');
        $message = "Приветствую!\nЯ обычный бот и не могу Вам ответить. Если у Вас есть какие-либо вопросы - обратитесь в официальный аккаунт технической поддержки " . $channel . "\n";
        $contacts = "Аккаунт технической поддержки:\n" . "{$accountName}: {$accountTag}";
        return $message . $contacts;
    }

    public function handleBusinessMessage(Update|array $response, string $adminChatId): void 
    {
        $message = $this->getText($response);
        $this->sendResponse($adminChatId, $message);
    }

    public function handlePersonalMessage(Update|array $response, array $params): void 
    {
        $chatId = $this->getChatId($response);
        $message = $this->getDefaultCallback($params);
        $this->sendResponse($chatId, $message);
    }

    public function handleGrouplMessage(Update|array $response, string $adminChatId): void  
    {
        $message = $this->getText($response);
        $this->sendResponse($adminChatId, $message);
    }

    public function isGroupMessage(Update|array $response): ?bool
    {
        if ($chatData = $this->getChatData($response)) {
            return isset($chatData['title']);
        }

        return false;
    }

    public function getGroupName(Update|array $response): ?string
    {
        if ($chatData = $this->getChatData($response)) {
            return $chatData['title'];
        }

        return null;
    }

    public function isBusinessMessage(Update|array $response): bool
    {
        return isset($response['business_message']);
    }

    public function getMessage(Update|array $response): ?array
    {
        if ($this->isBusinessMessage($response)) {
            return $response['business_message'];
        }

        if (isset($response['message'])) {
            return $response['message'];
        }

        return null;
    }

    public function isAdmin(int $id) {}

    public function getUserId(Update $response): ?string
    {
        if ($fromData = $this->getFromData($response)) {
            return $fromData['id'];
        }
        return null;
    }

    public function getChatId(Update|array $response): ?string
    {
        if ($chatData = $this->getChatData($response)) {
            return $chatData['id'];
        }

        return null;
    }

    public function getUsername(Update|array $response): ?string
    {
        $fromData = $this->getFromData($response);
        if ($fromData && isset($fromData['username'])) {
            return $fromData['username'];
        }

        return null;
    }

    public function getUserFullName(Update|array $response): ?string
    {
        $fromData = $this->getFromData($response);
        if (!$fromData) {
            return null;
        }

        $firstName = $fromData['first_name'];
        $lastName = isset($fromData['last_name']) ? $fromData['last_name'] : null;
        if ($lastName) {
            return "{$firstName} $lastName";
        }

        return $firstName;
    }

    public function getFromData(Update|array $response): ?array
    {
        if ($message = $this->getMessage($response)) {
            return $message['from'];
        }

        return null;
    }

    public function getChatData(Update|array $response): ?array
    {
        if ($message = $this->getMessage($response)) {
            return $message['chat'];
        }

        return null;
    }

    public function getText(Update|array $response): ?string
    {
        if ($message = $this->getMessage($response)) {
            return $message['text'];
        }

        return null;
    }

    public function getChatType(Update|array $response): ?string
    {
        if ($chatData = $this->getChatData($response)) {
            return $chatData['type'];
        }

        return null;
    }

    public function isPrivate(string $type): bool
    {
        return $type == 'private' ? true : false;
    }

    public function sendResponse(string $chatId, string $message): void
    {
        Telegram::sendMessage([
            'chat_id' => "$chatId",
            'text' => "$message",
        ]);
    }

    public function getAdminChatId(): string
    {
        return env('TELEGRAM_APPEAL_GROUP_ID');
    }
}
