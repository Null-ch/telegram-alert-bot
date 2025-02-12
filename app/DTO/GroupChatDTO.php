<?php

namespace App\DTO;

use App\Models\GroupChat;

class GroupChatDTO
{
    public function __construct(
        public string $account,
        public string $title,
        public string $chatId,
    )
    {
        $this->account = $account;
        $this->title = $title;
        $this->chatId = $chatId;
    }

    public function toArray(): array
    {
        return [
            'account' => $this->account,
            'title' => $this->title,
            'chat_id' => $this->chatId,
        ];
    }

    public static function fromModel(GroupChat $client): self
    {
        return new self(
            $client->account,
            $client->title,
            $client->chat_id,
        );
    }

    public function getChatId(): string
    {
        return $this->chatId;
    }

    public function getAccount(): string
    {
        return $this->account; 
    }
}
