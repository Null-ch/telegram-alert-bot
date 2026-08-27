<?php

namespace App\Interfaces;

use App\DTO\ChatGroupDTO;

interface ChatGroupServiceInterface
{
    public function create(array $data): ChatGroupDTO;

    public function update(int $id, array $data): ChatGroupDTO;

    /**
     * @return ChatGroupDTO[]
     */
    public function getByAccount(string $account): array;

    /**
     * @return int[]
     */
    public function getGroupChatIds(int $id): array;
}
