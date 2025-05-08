<?php

namespace App\Repositories;

use App\DTO\GroupChatDTO;
use App\Models\GroupChat;
use App\Interfaces\GroupChatRepositoryInterface;

class GroupChatRepository implements GroupChatRepositoryInterface
{
    public GroupChat $groupChat;

    public function __construct(
        GroupChat $groupChat
    ) {
        $this->groupChat = $groupChat;
    }

    public function getChatsByTag(string $tag): ?array
    {
        $groupChats = [];
        $chats = GroupChat::where('account', $tag)->get();
        foreach ($chats as $chat) {
            $groupChats[] = GroupChatDTO::fromModel($chat);
        }
        return $groupChats;
    }

    public function create(GroupChatDTO $dto): GroupChatDTO
    {
        $existing = $this->groupChat->where('chat_id', $dto->chatId)->first();
    
        if ($existing) {
            return $dto->fromModel($existing);
        }
        
        $ignoreList = $this->groupChat->firstOrCreate($dto->toArray());
        return $dto->fromModel($ignoreList);
    }

    public function getGroupChat(int $id): ?GroupChatDTO
    {
        $groupChatModel = GroupChat::findOrFail($id);
        $groupChatDTO = GroupChatDTO::fromModel($groupChatModel);

        return $groupChatDTO;
    }
}
