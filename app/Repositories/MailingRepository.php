<?php

namespace App\Repositories;

use App\DTO\MailingDTO;
use App\Models\Mailing;

class MailingRepository
{
    public Mailing $mailing;

    public function __construct(
        Mailing $mailing
    ) {
        $this->mailing = $mailing;
    }

    public function create(MailingDTO $dto): MailingDTO
    {
        $mailing = $this->mailing::create($dto->toArray());
        return $dto->fromModel($mailing);
    }

    /**
     * @param  array<int, array{id: int, title: ?string, chat_id: ?string}>  $sentChats
     * @param  array<int, array{id: int, title: ?string, chat_id: ?string, error: string}>  $failedChats
     */
    public function recordResults(int $id, array $sentChats, array $failedChats): void
    {
        $this->mailing::whereKey($id)->update([
            'sent_chats' => $sentChats,
            'failed_chats' => $failedChats,
            'status' => Mailing::STATUS_COMPLETED,
        ]);
    }
}
