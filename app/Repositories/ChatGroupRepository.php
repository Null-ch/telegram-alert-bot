<?php

namespace App\Repositories;

use App\DTO\ChatGroupDTO;
use App\Models\ChatGroup;
use App\Interfaces\ChatGroupRepositoryInterface;

class ChatGroupRepository implements ChatGroupRepositoryInterface
{
    public ChatGroup $chatGroup;

    public function __construct(
        ChatGroup $chatGroup
    ) {
        $this->chatGroup = $chatGroup;
    }

    public function create(ChatGroupDTO $dto): ChatGroupDTO
    {
        $chatGroup = $this->chatGroup->create($dto->toArray());
        $chatGroup->groupChats()->sync($dto->chatIds);

        return ChatGroupDTO::fromModel($chatGroup->fresh('groupChats'));
    }

    public function update(int $id, ChatGroupDTO $dto): ChatGroupDTO
    {
        $chatGroup = $this->chatGroup->findOrFail($id);
        $chatGroup->update($dto->toArray());
        $chatGroup->groupChats()->sync($dto->chatIds);

        return ChatGroupDTO::fromModel($chatGroup->fresh('groupChats'));
    }

    public function getChatGroup(int $id): ?ChatGroupDTO
    {
        $chatGroup = $this->chatGroup->with('groupChats')->findOrFail($id);

        return ChatGroupDTO::fromModel($chatGroup);
    }

    public function getByAccount(string $account): array
    {
        $chatGroups = $this->chatGroup->where('account', $account)
            ->orderBy('title', 'ASC')
            ->get();

        $result = [];
        foreach ($chatGroups as $chatGroup) {
            $result[] = ChatGroupDTO::fromModel($chatGroup);
        }

        return $result;
    }

    public function getGroupChatIds(int $id): array
    {
        $chatGroup = $this->chatGroup->with('groupChats')->findOrFail($id);

        return $chatGroup->groupChats->pluck('id')->all();
    }
}
