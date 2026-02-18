<?php

namespace App\DTO;

class MessageReactionDTO
{
    private array $data;

    public function __construct(array $update)
    {
        // ожидаем массив вида $update['message_reaction']
        $this->data = $update['message_reaction'] ?? [];
    }

    /** Получить ID группы / чата */
    public function getChatId(): ?int
    {
        return $this->data['chat']['id'] ?? null;
    }

    /** Получить название группы (title) */
    public function getChatTitle(): ?string
    {
        return $this->data['chat']['title'] ?? null;
    }

    /** Получить ID сообщения */
    public function getMessageId(): ?int
    {
        return $this->data['message_id'] ?? null;
    }

    /** Получить ID пользователя */
    public function getUserId(): ?int
    {
        return $this->data['user']['id'] ?? null;
    }

    /** Получить first_name пользователя */
    public function getUserFirstName(): ?string
    {
        return $this->data['user']['first_name'] ?? null;
    }

    /** Получить last_name пользователя */
    public function getUserLastName(): ?string
    {
        return $this->data['user']['last_name'] ?? null;
    }

    /** Получить username пользователя */
    public function getUsername(): ?string
    {
        return $this->data['user']['username'] ?? null;
    }

    /** Проверка: есть ли старая реакция */
    public function hasOldReaction(): bool
    {
        return !empty($this->data['old_reaction']);
    }

    /** Получить массив старых реакций (emoji) */
    public function getOldReactions(): array
    {
        return array_map(fn($r) => $r['emoji'] ?? null, $this->data['old_reaction'] ?? []);
    }

    /** Получить массив новых реакций (emoji) */
    public function getNewReactions(): array
    {
        return array_map(fn($r) => $r['emoji'] ?? null, $this->data['new_reaction'] ?? []);
    }

    /** Получить timestamp реакции */
    public function getDate(): ?int
    {
        return $this->data['date'] ?? null;
    }
}
