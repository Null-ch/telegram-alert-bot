<?php

namespace App\DTO;

use App\Models\ArchiveMessage;

class ArchiveMessageDTO
{
    public null|int|string $id;
    public function __construct(
        public string $text,
        public string $chat,
        public ?string $chatId,
        public string $channelType,
        public int|string $clientId,
        public int $messageId,
    )
    {
        $this->text = $text;
        $this->chat = $chat;
        $this->chatId = $chatId;
        $this->channelType = $channelType;
        $this->clientId = $clientId;
        $this->messageId = $messageId;
    }

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'chat' => $this->chat,
            'chat_id' => $this->chatId,
            'channel_type' => $this->channelType,
            'client_id' => $this->clientId,
            'message_id' => $this->messageId,
        ];
    }

    public static function fromModel(ArchiveMessage $appeal): self
    {
        return new self(
            $appeal->text,
            $appeal->chat,
            $appeal->chat_id,
            $appeal->channel_type,
            $appeal->client_id,
            $appeal->message_id,
        );
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getChatName(): string
    {
        return $this->chat;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getMessageId(): int
    {
        return $this->messageId;
    }

    public function getChannelType(): string
    {
        return $this->channelType;
    }
}
