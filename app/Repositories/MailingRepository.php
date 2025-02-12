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
        $ignoreList = $this->mailing::create($dto->toArray());
        return $dto->fromModel($ignoreList);
    }
}
