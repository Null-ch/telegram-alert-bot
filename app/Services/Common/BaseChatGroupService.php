<?php

namespace App\Services\Common;

use Illuminate\Support\Arr;
use App\DTO\ChatGroupDTO;
use App\Repositories\ChatGroupRepository;
use App\Interfaces\ChatGroupServiceInterface;

class BaseChatGroupService implements ChatGroupServiceInterface
{
    public ChatGroupRepository $chatGroupRepository;

    public function __construct(
        ChatGroupRepository $chatGroupRepository,
    ) {
        $this->chatGroupRepository = $chatGroupRepository;
    }

    public function create(array $data): ChatGroupDTO
    {
        return $this->chatGroupRepository->create(new ChatGroupDTO(
            Arr::get($data, 'account'),
            Arr::get($data, 'title'),
            Arr::get($data, 'chat_ids', []),
        ));
    }

    public function update(int $id, array $data): ChatGroupDTO
    {
        return $this->chatGroupRepository->update($id, new ChatGroupDTO(
            Arr::get($data, 'account'),
            Arr::get($data, 'title'),
            Arr::get($data, 'chat_ids', []),
        ));
    }

    public function getByAccount(string $account): array
    {
        return $this->chatGroupRepository->getByAccount($account);
    }

    public function getGroupChatIds(int $id): array
    {
        return $this->chatGroupRepository->getGroupChatIds($id);
    }
}
