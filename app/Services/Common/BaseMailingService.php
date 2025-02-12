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
}
