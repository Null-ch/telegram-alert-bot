<?php

namespace App\DTO;

use App\Models\ChatGroup;

class ChatGroupDTO
{
    /**
     * @param int[] $chatIds
     */
    public function __construct(
        public string $account,
        public string $title,
        public array $chatIds = [],
        public ?int $id = null,
    )
    {
        $this->account = $account;
        $this->title = $title;
        $this->chatIds = $chatIds;
        $this->id = $id;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'account' => $this->account,
            'title' => $this->title,
        ];
    }

    public static function fromModel(ChatGroup $chatGroup): self
    {
        return new self(
            $chatGroup->account,
            $chatGroup->title,
            $chatGroup->groupChats->pluck('id')->all(),
            $chatGroup->id,
        );
    }
}
