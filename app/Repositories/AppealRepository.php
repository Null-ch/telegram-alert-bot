<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\DTO\AppealDTO;
use App\Models\Appeal;
use App\Interfaces\AppealRepositoryInterface;

class AppealRepository implements AppealRepositoryInterface
{
    public function create(AppealDTO $dto): AppealDTO
    {
        $appeal = new Appeal();
        $appeal->text = $dto->text;
        $appeal->chat = $dto->chat;
        $appeal->chat_id = $dto->chatId;
        $appeal->channel_type = $dto->channelType;
        $appeal->client_id = $dto->clientId;
        $appeal->message_id = $dto->messageId;
        $appeal->save();

        return $dto::fromModel($appeal);
    }

    public function getLastAppeal(int|string $id): ?Appeal
    {
        $lastApeeal = Appeal::active()->where('client_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastApeeal) {
            return $lastApeeal;
        }

        return null;
    }

    public function getAppeal(int $id): ?AppealDTO
    {
        $appeal = Appeal::findOrFail($id);
        $appealDTO = new AppealDTO(
            $appeal->text,
            $appeal->chat,
            $appeal->chat_id,
            $appeal->channel_type,
            $appeal->client_id,
            $appeal->message_id
        );

        return $appealDTO;
    }

    public function getAppeals(int $count, string $sort): ?array
    {
        $query = Appeal::query();

        switch ($sort) {
            case 'DESC':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'ASC':
                $query->orderBy('created_at', 'ASC');
                break;
            default:
                throw new \InvalidArgumentException("Invalid sort parameter. Must be either 'desc' or 'asc'.");
        }

        $results = $query->take($count)->get();
        $appeals = [];
        foreach ($results as $result) {
            $appeals[] = new AppealDTO(
                $result->text,
                $result->chat,
                $result->chat_id,
                $result->channel_type,
                $result->client_id,
                $result->message_id
            );
        }
        return $appeals;
    }

    public function getAppealsByDateRange(string $startDate, string $endDate): array
    {
        $appeals = Appeal::whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ])->get();

        $appealsDTO = [];
        foreach ($appeals as $appeal) {
            $appealsDTO[] = AppealDTO::fromModel($appeal);
        }

        return $appealsDTO;
    }
}
