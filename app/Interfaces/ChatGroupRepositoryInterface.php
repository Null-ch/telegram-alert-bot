<?php

namespace App\Interfaces;

use App\DTO\ChatGroupDTO;

interface ChatGroupRepositoryInterface
{
    public function create(ChatGroupDTO $dto): ChatGroupDTO;

    public function update(int $id, ChatGroupDTO $dto): ChatGroupDTO;

    public function getChatGroup(int $id): ?ChatGroupDTO;

    /**
     * @return ChatGroupDTO[]
     */
    public function getByAccount(string $account): array;

    /**
     * @return int[]
     */
    public function getGroupChatIds(int $id): array;
}
