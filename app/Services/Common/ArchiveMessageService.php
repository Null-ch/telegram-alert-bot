<?php

namespace App\Services\Common;

use Throwable;
use Illuminate\Support\Arr;
use App\DTO\ArchiveMessageDTO;
use Illuminate\Support\Facades\Log;
use App\Repositories\ArchiveMessageRepository;

class ArchiveMessageService
{
    public ArchiveMessageRepository $archiveMessageRepository;

    public function __construct(
        ArchiveMessageRepository $archiveMessageRepository
    ) {
        $this->archiveMessageRepository = $archiveMessageRepository;
    }
    public function create(array $dataArray): ?ArchiveMessageDTO
    {
        try {
            $appealData = new ArchiveMessageDTO(
                Arr::get($dataArray, 'text'),
                Arr::get($dataArray, 'chat'),
                Arr::get($dataArray, 'chatId'),
                Arr::get($dataArray, 'channelType'),
                Arr::get($dataArray, 'clientId'),
                Arr::get($dataArray, 'messageId'),
            );
            $this->archiveMessageRepository->create($appealData);

            return $appealData;
        } catch (Throwable $e) {
            Log::error("Error when create client: {$e->getMessage()}", $e->getTrace());
            return null;
        }
    }
}
