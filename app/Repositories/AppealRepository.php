<?php

namespace App\Repositories;

use App\DTO\AppealDTO;
use App\Models\Appeal;

class AppealRepository
{
    public function create(AppealDTO $dto): AppealDTO
    {
        $appeal = new Appeal();
        $appeal->text = $dto->text;
        $appeal->chat = $dto->chat;
        $appeal->channel_type = $dto->channelType;
        $appeal->client_id = $dto->clientId;
        $appeal->message_id = $dto->messageId;
        $appeal->save();

        return $dto->fromModel($appeal);
    }

    public function getLastAppeal(int|string $id, string $channelType, string $chat): ?Appeal
    {
        $lastApeeal = Appeal::active()->where('client_id', $id)
            ->where('channel_type', $channelType)
            ->where('chat', $chat)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastApeeal) {
            return $lastApeeal;
        }

        return null;
    }
}
