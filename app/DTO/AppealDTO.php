<?php

namespace App\DTO;

use App\Interfaces\DTOInterface;
use App\Models\Appeal;
use Illuminate\Support\Facades\Validator;

class AppealDTO implements DTOInterface
{
    public ?string $date;
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

    public function isValid(): bool
    {
        $validator = Validator::make(
            $this->toArray(),
            [
                'text' => 'required|string',
                'chat_type' => 'required|string',
                'channel_type' => 'required|string',
                'client_type' => 'required|integer',
                'message_id' => 'required|integer',
            ]
        );

        return $validator->passes();
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

    public static function fromModel(Appeal $appeal): self
    {
        $appealDTO = new self(
            $appeal->text,
            $appeal->chat,
            $appeal->chat_id,
            $appeal->channel_type,
            $appeal->client_id,
            $appeal->message_id,
        );
        $appealDTO->date = $appeal->created_at;

        return $appealDTO;
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