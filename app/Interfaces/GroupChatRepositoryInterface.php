<?php

namespace App\Interfaces;

interface GroupChatRepositoryInterface
{
    public function getChatsByTag(string $tag): ?array;
}
