<?php

namespace App\Services\Common;

use GuzzleHttp\Client;
use App\DTO\MailingDTO;
use App\Enums\ChatType;
use App\DTO\ApiResponseDTO;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DTO\MessageReactionDTO;
use Telegram\Bot\Objects\Update;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\ResponseInterface;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Interfaces\TelegramServiceInterface;
use App\Services\Common\BaseGroupChatService;
use App\Services\Common\ArchiveMessageService;

class BaseTelegramService implements TelegramServiceInterface
{
    public Client $client;
    public BaseAppealService $baseAppealService;
    public BaseClientService $baseClientService;
    public BaseIgnoreListService $baseIgnoreListService;
    public BaseGroupChatService $baseGroupChatService;
    public BaseMailingService $baseMailingService;
    public ArchiveMessageService $archiveMessageService;
    public OllamaService $ollamaService;
    public BaseCommandService $baseCommandService;

    public function __construct(
        BaseAppealService $baseAppealService,
        BaseClientService $baseClientService,
        BaseIgnoreListService $baseIgnoreListService,
        BaseGroupChatService $baseGroupChatService,
        BaseMailingService $baseMailingService,
        ArchiveMessageService $archiveMessageService,
        OllamaService $ollamaService,
    ) {
        $this->client = new Client;
        $this->baseAppealService = $baseAppealService;
        $this->baseClientService = $baseClientService;
        $this->baseIgnoreListService = $baseIgnoreListService;
        $this->baseGroupChatService = $baseGroupChatService;
        $this->baseMailingService = $baseMailingService;
        $this->archiveMessageService = $archiveMessageService;
        $this->ollamaService = $ollamaService;
    }

    public function setWebhook(string $prefix): ApiResponseDTO
    {
        $token = $this->getToken($prefix);
        $telegramApiUrl = $this->getTelegramApiUrl($token, 'setWebhook');
        $webhookUrl = $this->getWebhookUrl($prefix);

        $response = $this->client->request('POST', $telegramApiUrl, [
            'json' => [
                'url' => $webhookUrl,
                'allowed_updates' => [
                    'message',
                    'edited_message',
                    'message_reaction',
                    'message_reaction_count',
                    'callback_query',
                    'chat_member',
                    'my_chat_member',
                ],
            ],
        ]);

        return $this->responseProcessing($response);
    }

    public function removeWebhook(string $prefix): ApiResponseDTO
    {
        $token = $this->getToken($prefix);
        $telegramApiUrl = $this->getTelegramApiUrl($token, 'deleteWebhook');
        $response = $this->client->request('GET', $telegramApiUrl);
        return $this->responseProcessing($response);
    }

    public function getToken(string $prefix): string
    {
        return match ($prefix) {
            'test' => env('TELEGRAM_TEST_BOT_TOKEN'),
            'botInfocur' => env('TELEGRAM_INFOCUR_BOT_TOKEN'),
            'botMo' => env('TELEGRAM_MO_BOT_TOKEN'),
            'botOrion' => env('TELEGRAM_ORION_BOT_TOKEN'),
            'infocur' => env('TELEGRAM_INFOCUR_BOT_TOKEN'),
            'mo' => env('TELEGRAM_MO_BOT_TOKEN'),
            'orion' => env('TELEGRAM_ORION_BOT_TOKEN'),
            default => throw new \RuntimeException('Telegram token not found for selected account'),
        };
    }

    public function getTelegramApiUrl(string $token, string $method): string
    {
        return 'https://api.telegram.org/bot' . $token . '/' . $method;
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

    /**
     * Метод позволяет обрабатывать личные сообщения, с последующей пересылкой в чаты
     * 
     * @param Update|array $response
     * @param string $currentAccount
     * 
     * @return string|null
     */
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
        if ($text) {
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

        return null;
    }

    public function handlePersonalMessage(array $params): string
    {
        $message = $this->getDefaultCallback($params);
        return $message;
    }

    /**
     * Метод позволяет обрабатывать групповые сообщения, с последующей пересылкой в чаты
     * 
     * @param Update|array $response
     * @param string $currentAccount
     * 
     * @return string|null
     */
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

        if ($text = $this->getText($response)) {
            $chatId = Arr::get($this->getChatData($response), 'id');
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
                'chatId' => $chatId,
                'channelType' => $currentAccount,
                'clientId' => $newClientData->getClientId(),
                'messageId' => $this->getMessageId($response),
            ]);

            $this->baseGroupChatService->create(
                [
                    'title' => $chat,
                    'account' => $currentAccount,
                    'chatId' => $chatId,
                ]
            );

            return $message;
        }

        return null;
    }

    /**
     * Метод позволяет обрабатывать любое сообщение и заносить его в базу, без пересылки в чаты
     * 
     * @param Update|array $response
     * @param string $currentAccount
     * 
     * @return void
     */
    public function handleMessage(Update|array $response, string $currentAccount): void
    {
        $isMessage = $this->isMessage($response);
        if (!$isMessage) {
            return;
        }
        $userId = $this->getUserId($response);
        if ($this->isIgnored($userId)) {
            return;
        }

        $text = $this->getText($response);
        $chatName = $this->getChatName($response) ? $this->getChatName($response) : ChatType::private->value;
        $username = $this->getUserFullName($response);
        $chatId = Arr::get($this->getChatData($response), 'id');

        $newClientData = $this->baseClientService->createClient([
            'fullName' => $username,
            'tgId' => $userId,
            'channelType' => $currentAccount,
        ]);
        $this->archiveMessageService->create([
            'text' => $text,
            'chat' => $chatName,
            'chatId' => $chatId,
            'channelType' => $currentAccount,
            'clientId' => $newClientData->getClientId(),
            'messageId' => $this->getMessageId($response),
        ]);
    }

    public function handleMessageWithAi(Update|array $response, string $currentAccount)
    {
        $isMessage = $this->isMessage($response);
        if (!$isMessage) {
            return;
        }

        $text = $this->getText($response);
        $chatId = Arr::get($this->getChatData($response), 'id');
        $message = $this->ollamaService->sendRequest($text);
        $this->sendMessage($chatId, $message, $currentAccount);

        return;
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

    public function isReaction(Update|array $response): bool
    {
        return isset($response['message_reaction']);
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
            return Arr::get($chatData, 'id');
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
        if (!$message = $this->getMessage($response)) {
            return null;
        }

        if (isset($message['text'])) {
            return $message['text'];
        }

        if (isset($message['caption'])) {
            return $message['caption'];
        }

        if (isset($message['photo'])) {
            return 'Отправлено изображение без текста';
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

    public function sendMessage(string $chatId, string $message, string $botName, string $parseMode = 'html'): void
    {
        try {
            if (stripos($botName, 'bot') !== false) {
                $tag = Str::lower(str_replace('bot', '', $botName));
            } else {
                $tag = Str::lower($botName);
            }
            if (empty($tag)) {
                return;
            }

            $token = $this->getToken($tag);

            $this->client->post(env('TELEGRAM_BASE_URL') . "/bot{$token}/sendMessage", [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => $parseMode
                ]
            ]);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $errorMessage = "Chat ID: {$chatId}, botName {$botName} Ошибка: {$error}\n";
            $this->sendResponse(env('TELEGRAM_ERROR_ALERT_CHAT_ID'), $errorMessage, 'test');
        }
    }

    public function getAdminChatId(): string
    {
        return env('TELEGRAM_APPEAL_GROUP_ID');
    }

    public function getFunChatId(): string
    {
        return env('TELEGRAM_FUN_GROUP');
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
        if ($this->baseIgnoreListService->getIgnoredByTgId($id)) {
            return true;
        }

        return false;
    }

    public function sendMailing(Request $request): void
    {
        $tag = $request->get('account');
        $message = $request->get('message');
        $chatIds = $request->get('chat_ids') ?? [];
        $file = $request->file('file') ?? null;
        if ($file instanceof UploadedFile) {
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('temp_uploads', $filename);
            $fullPath = Storage::disk('local')->path($path);

            $storedFile = new UploadedFile(
                $fullPath,
                $file->getClientOriginalName(),
                $file->getMimeType(),
                null,
                true
            );
        }

        $this->baseMailingService->create(new MailingDTO(
            $message,
            $tag,
        ));

        foreach ($chatIds as $chatId) {
            $chatIdResolved = $this->baseGroupChatService->getGroupChatId($chatId);

            if ($file) {
                $this->sendDocument($chatIdResolved, $storedFile, $tag, $message);
            } else {
                $this->sendMessage($chatIdResolved, $message, $tag);
            }
        }

        if ($file) {
            Storage::disk('local')->delete($path);
        }
    }

    public function sendDocument(string $chatId, UploadedFile $file, string $tag, ?string $caption = null): void
    {
        if (empty($tag)) {
            return;
        }

        $token = $this->getToken($tag);
        $multipart = [
            [
                'name'     => 'chat_id',
                'contents' => $chatId,
            ],
            [
                'name'     => 'document',
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $file->getClientOriginalName(),
            ],
        ];

        if ($caption) {
            $multipart[] = [
                'name'     => 'caption',
                'contents' => $caption,
            ];
        }

        $this->client->post(env('TELEGRAM_BASE_URL') . "/bot{$token}/sendDocument", [
            'multipart' => $multipart,
            'parse_mode' => "markdown"
        ]);
    }

    public function handleReaction(MessageReactionDTO $dto, string $currentAccount)
    {
        return;
    }
}
