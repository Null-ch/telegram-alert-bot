<?php

namespace App\Interfaces;

use App\DTO\ApiResponseDTO;
use Telegram\Bot\Objects\Update;
use Psr\Http\Message\ResponseInterface;

interface TelegramServiceInterface
{
    public function setWebhook(string $prefix): ApiResponseDTO;

    public function removeWebhook(string $prefix): ApiResponseDTO;

    public function getToken(string $prefix): string;

    public function getTelegramApiUrl(string $token, string $type): ?string;

    public function getWebhookUrl(string $prefix): string;

    public function responseProcessing(ResponseInterface $response): ApiResponseDTO;

    public function getErrorMessage(int $statusCode): string;

    public function getDefaultCallback(array $params): string;

    public function handleBusinessMessage(Update|array $response, string $currentAccount): ?string;

    public function handlePersonalMessage(array $params): string;

    public function handleGrouplMessage(Update|array $response, string $currentAccount): ?string;

    public function isGroupMessage(Update|array $response): ?bool;

    public function getChatName(Update|array $response): ?string;

    public function isBusinessMessage(Update|array $response): bool;
    public function getMessage(Update|array $response): ?array;
    public function isAdmin(int $id);

    public function getUserId(Update $response): ?string;

    public function getChatId(Update|array $response): ?string;

    public function getUsername(Update|array $response): ?string;

    public function getUserFullName(Update|array $response): ?string;

    public function getFromData(Update|array $response): ?array;

    public function getChatData(Update|array $response): ?array;

    public function getText(Update|array $response): ?string;

    public function isPrivate(string $type): bool;

    public function sendResponse(string $chatId, string $message, string $botName): void;

    public function getAdminChatId(): string;

    public function generateForwardedMessage(array $params): string;
    public function getMessageId(Update|array $response): ?string;

    public function isMessage(Update|array $response): bool;

    public function isIgnored(int|string $id): bool;
}
