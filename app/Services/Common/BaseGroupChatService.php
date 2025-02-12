<?php

namespace App\Services\Common;

use App\DTO\GroupChatDTO;
use Illuminate\Support\Arr;
use App\Repositories\GroupChatRepository;
use App\Interfaces\GroupChatServiceInterface;

class BaseGroupChatService implements GroupChatServiceInterface
{
    public GroupChatRepository $groupChatRepository;
    public function __construct(
        GroupChatRepository $groupChatRepository,
    ) {
        $this->groupChatRepository = $groupChatRepository;
    }

    public function getChatsByTag(string $tag): array
    {
        return $this->groupChatRepository->getChatsByTag($tag);
    }

    public function create(array $data): GroupChatDTO
    {
        $account = $this->getAccountByTag(Arr::get($data, 'account'));
        return $this->groupChatRepository->create(new GroupChatDTO(
            $account,
            Arr::get($data, 'title'),
            Arr::get($data, 'chatId'),
        ));
    }

    public function getAccountByTag(string $accountTag)
    {
        return match ($accountTag) {
            'test' => 'test',
            '@HelpdeskOrionTerminal' => 'botOrion',
            '@HelpDesk_MO' => 'botMo',
            '@HelpdeskTerminal' => 'botInfocur',
        };
    }
}
