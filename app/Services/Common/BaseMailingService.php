<?php

namespace App\Services\Common;

use App\DTO\MailingDTO;
use App\Repositories\MailingRepository;

class BaseMailingService
{
    public MailingRepository $mailingRepository;
    public function __construct(
        MailingRepository $mailingRepository,
    ) {
        $this->mailingRepository = $mailingRepository;
    }

    public function create(MailingDTO $dto): MailingDTO
    {
        return $this->mailingRepository->create($dto);
    }

    /**
     * @param  array<int, array{id: int, title: ?string, chat_id: ?string}>  $sentChats
     * @param  array<int, array{id: int, title: ?string, chat_id: ?string, error: string}>  $failedChats
     */
    public function recordResults(int $id, array $sentChats, array $failedChats): void
    {
        $this->mailingRepository->recordResults($id, $sentChats, $failedChats);
    }
}
