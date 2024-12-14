<?php

namespace App\Services\Common;

use Throwable;
use Carbon\Carbon;
use App\DTO\AppealDTO;
use App\Enums\ChatType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Repositories\AppealRepository;
use App\Interfaces\AppealServiceInterface;

class BaseAppealService implements AppealServiceInterface
{
    public AppealRepository $appealRepository;

    public function __construct(
        AppealRepository $appealRepository
    ) {
        $this->appealRepository = $appealRepository;
    }

    public function createAppeal(array $appealDataArray): ?AppealDTO
    {
        try {
            $appealData = new AppealDTO(
                Arr::get($appealDataArray, 'text'),
                Arr::get($appealDataArray, 'chat'),
                Arr::get($appealDataArray, 'channelType'),
                Arr::get($appealDataArray, 'clientId'),
                Arr::get($appealDataArray, 'messageId'),
            );
            $this->appealRepository->create($appealData);

            return $appealData;
        } catch (Throwable $e) {
            Log::error("Error when create client: {$e->getMessage()}", $e->getTrace());
            return null;
        }
    }

    public function isExpiredTimeout(int|string $id, string $channelType, ?string $chat)
    {
        if (!$chat) {
            $chat = ChatType::private->value;
        }

        $lastAppeal = $this->appealRepository->getLastAppeal($id, $channelType, $chat);

        if (!$lastAppeal) {
            return true;
        }

        $lastAppealCreatedAt = Carbon::parse($lastAppeal->created_at);
        $minutesPassed = $lastAppealCreatedAt->diffInMinutes(Carbon::now());

        if ($minutesPassed >= env('TIMEOUT_TO_NEXT_MESSAGE')) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getAppeal(int $id): ?AppealDTO
    {
        return $this->appealRepository->getAppeal($id);
    }

    public function getAppeals(int $count, string $sort): ?array
    {
        return $this->appealRepository->getAppeals($count, $sort);
    }
}
