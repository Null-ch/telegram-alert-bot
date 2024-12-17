<?php

namespace App\Services\Common;

use App\DTO\IgnoreListDTO;
use App\Repositories\IgnoreListRepository;

class BaseIgnoreListService
{
    public IgnoreListRepository $ignoreListRepository;
    public function __construct(
        IgnoreListRepository $ignoreListRepository,
    ) {
        $this->ignoreListRepository = $ignoreListRepository;
    }

    public function create(IgnoreListDTO $dto): IgnoreListDTO
    {
        return $this->ignoreListRepository->create($dto);
    }

    public function getIgnoredByTgId(int $tgId): ?IgnoreListDTO
    {
        $inoreListItem = $this->ignoreListRepository->getIgnoredByTgId($tgId);

        return $inoreListItem;
    }
}
