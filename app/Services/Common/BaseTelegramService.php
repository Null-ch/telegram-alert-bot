<?php

namespace App\Services\Common;

use GuzzleHttp\Client;
use App\Enums\ChatType;
use App\DTO\ApiResponseDTO;
use Illuminate\Support\Arr;
use Telegram\Bot\Objects\Update;
use Psr\Http\Message\ResponseInterface;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Interfaces\TelegramServiceInterface;

class BaseTelegramService implements TelegramServiceInterface
{
    //TODO:implement ID loading from the ignore list 
    const ADMINS = [
        '6899147031',
        '6256784114',
        '6960195534',
        '395590080',
        '344590941',
        '615007058',
        '774982582',
        '5000707181',
    ];

    public Client $client;
    public BaseAppealService $baseAppealService;
    public BaseClientService $baseClientService;
    public function __construct(
        BaseAppealService $baseAppealService,
        BaseClientService $baseClientService,
    ) {
        $this->client = new Client;
        $this->baseAppealService = $baseAppealService;
        $this->baseClientService = $baseClientService;
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
    public function getToken(string $prefix): string
    {
        return env('TELEGRAM_' . strtoupper($prefix) . '_BOT_TOKEN');
    }
    public function getTelegramApiUrl(string $token, string $type): ?string
    {
        if ($type == 'set') {
            return 'https://api.telegram.org/bot' . $token . '/setWebhook?url=';
        }
        if ($type == 'remove') {
            return 'https://api.telegram.org/bot' . $token . '/deleteWebhook';
        }

        return null;
    }
    public function getWebhookUrl(string $prefix): string
    {
        return route('telegram_webhook', ['prefix' => $prefix]);
    }
    public function responseProcessing(ResponseInterface $response): ApiResponseDTO
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
    public function getErrorMessage(int $statusCode): string
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
        $accountName = Arr::get($params, 'accountName');
        $accountTag = Arr::get($params, 'accountTag');
        $message = "Приветствую!\nЯ обычный бот и не могу Вам ответить. Если у Вас есть какие-либо вопросы - обратитесь в официальный аккаунт технической поддержки\n";
        $contacts = "Контакт технической поддержки:\n" . "{$accountName}: {$accountTag}";
        return $message . $contacts;
    }

    public function handleBusinessMessage(Update|array $response, string $currentAccount): ?string
    {
        $isMessage = $this->isMessage($response);
        if (!$isMessage) {
            return null;
        }

        $tgId = $this->getUserId($response);
        if ($this->isIgnored($tgId)) {
            return null;
        }

        $chat = $this->getChatName($response);
        $clientData = $this->baseClientService->getClientByTgId($tgId);

        if ($clientData) {
            $isExpiredTimeout = $this->baseAppealService->isExpiredTimeout(
                $clientData->getClientId(),
                $currentAccount,
                $chat
            );
            if (!$isExpiredTimeout) {
                return null;
            }
        }

        $text = $this->getText($response);
        $nick = $this->getUsername($response);
        $username = $this->getUserFullName($response);
        $forwardedMessage = $this->generateForwardedMessage([
            'currentAccount' => $currentAccount,
            'text' => $text,
            'chat' => $chat,
            'nick' => $nick,
            'username' => $username,
        ]);

        $newClientData = $this->baseClientService->createClient([
            'fullName' => $username,
            'tgId' => $tgId,
        ]);

        $this->baseAppealService->createAppeal([
            'text' => $text,
            'chat' => $chat ? $chat : ChatType::private->value,
            'channelType' => $currentAccount,
            'clientId' => $newClientData->getClientId(),
            'messageId' => $this->getMessageId($response),
        ]);

        return $forwardedMessage;
    }

    public function handlePersonalMessage(array $params): string
    {
        $message = $this->getDefaultCallback($params);
        return $message;
    }

    public function handleGrouplMessage(Update|array $response, string $currentAccount): ?string
    {
        $isMessage = $this->isMessage($response);
        if (!$isMessage) {
            return null;
        }

        $tgId = $this->getUserId($response);
        if ($this->isIgnored($tgId)) {
            return null;
        }

        $text = $this->getText($response);
        $chat = $this->getChatName($response);
        $nick = $this->getUsername($response);
        $username = $this->getUserFullName($response);

        $clientData = $this->baseClientService->getClientByTgId($tgId);

        if ($clientData) {
            $isExpiredTimeout = $this->baseAppealService->isExpiredTimeout(
                $clientData->getClientId(),
                $currentAccount,
                $chat
            );
            if (!$isExpiredTimeout) {
                return null;
            }
        }

        $message = $this->generateForwardedMessage([
            'currentAccount' => $currentAccount,
            'text' => $text,
            'chat' => $chat,
            'nick' => $nick,
            'username' => $username,
        ]);

        $newClientData = $this->baseClientService->createClient([
            'fullName' => $username,
            'tgId' => $this->getUserId($response),
            'channelType' => $currentAccount,
        ]);

        $this->baseAppealService->createAppeal([
            'text' => $text,
            'chat' => $chat ? $chat : ChatType::private->value,
            'channelType' => $currentAccount,
            'clientId' => $newClientData->getClientId(),
            'messageId' => $this->getMessageId($response),
        ]);

        return $message;
    }

    public function isGroupMessage(Update|array $response): ?bool
    {
        if ($chatData = $this->getChatData($response)) {
            return isset($chatData['title']);
        }

        return false;
    }

    public function getChatName(Update|array $response): ?string
    {
        if ($chatData = $this->getChatData($response)) {
            return isset($chatData['title']) ? $chatData['title'] : null;
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
        if ($chatData = $this->getFromData($response)) {
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
            return isset($message['text']) ? $message['text'] : null;
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

    public function isPrivate(?string $type): bool
    {
        return $type == 'private';
    }

    public function sendResponse(string $chatId, string $message, string $botName): void
    {
        try {
            if (Telegram::getChat(['chat_id' => $chatId])) {
                Telegram::bot($botName)->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "$message",
                ]);
            }
        } finally {
            //
        }
    }

    public function getAdminChatId(): string
    {
        return env('TELEGRAM_APPEAL_GROUP_ID');
    }

    public function generateForwardedMessage(array $params): string
    {
        $currentAccount = Arr::get($params, 'currentAccount');
        $text = Arr::get($params, 'text');
        $chat = Arr::get($params, 'chat') ? Arr::get($params, 'chat') : null;
        $nick = Arr::get($params, 'nick') ? Arr::get($params, 'nick') : null;
        $username = Arr::get($params, 'username');
        $accountPart = "Аккаунт: {$currentAccount}\n";
        $messageBodyPart = "Содержимое сообщения:\n{$text}\n\n";
        $fromPart = $chat ? "Пришло из: {$chat}\n" : "Пришло из: Личные сообщения\n";
        $userNickPart = $nick ? "Ник пользователя в ТГ: @{$nick}\n" : "Ник пользователя в ТГ: Неизвестно\n";
        $usernamePart = "Пользователь: {$username}";
        $message = $accountPart . $messageBodyPart . $fromPart . $userNickPart . $usernamePart;

        return $message;
    }

    public function getMessageId(Update|array $response): ?string
    {
        if ($message = $this->getMessage($response)) {
            return $message['message_id'];
        }

        return null;
    }

    public function isMessage(Update|array $response): bool
    {
        if ($this->getMessage($response)) {
            return true;
        }

        return false;
    }

    public function isIgnored(int|string $id): bool
    {
        if (in_array($id, self::ADMINS)) {
            return true;
        }

        return false;
    }
}
