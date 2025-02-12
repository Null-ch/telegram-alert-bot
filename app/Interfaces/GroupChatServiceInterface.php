<?php

namespace App\Interfaces;

interface GroupChatServiceInterface
{
    public function getChatsByTag(string $tag): array;
}
