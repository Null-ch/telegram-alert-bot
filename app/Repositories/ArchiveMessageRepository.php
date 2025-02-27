<?php

namespace App\Repositories;

use App\DTO\ArchiveMessageDTO;
use App\Models\ArchiveMessage;

class ArchiveMessageRepository
{
    public function create(ArchiveMessageDTO $dto): ArchiveMessageDTO
    {
        $archiveMessage = new ArchiveMessage();
        $archiveMessage->text = $dto->text;
        $archiveMessage->chat = $dto->chat;
        $archiveMessage->chat_id = $dto->chatId;
        $archiveMessage->channel_type = $dto->channelType;
        $archiveMessage->client_id = $dto->clientId;
        $archiveMessage->message_id = $dto->messageId;
        $archiveMessage->save();

        return $dto::fromModel($archiveMessage);
    }

    public function getMessages(string $dateStart, string $dateEnd = null)
    {
        return ArchiveMessage::dateRange($dateStart, $dateEnd)
            ->groupByChatId()
            ->get();
    }
}
