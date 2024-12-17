<?php

namespace App\Repositories;

use App\DTO\IgnoreListDTO;
use App\Models\IgnoreList;
use App\Interfaces\IgnoreListRepositoryInterface;

class IgnoreListRepository implements IgnoreListRepositoryInterface
{
    public IgnoreList $ignoreList;

    public function __construct(
        IgnoreList $ignoreList
    ) {
        $this->ignoreList = $ignoreList;
    }

    public function create(IgnoreListDTO $dto): IgnoreListDTO
    {
        $ignoreList = $this->ignoreList::where('tg_id', $dto->tgId)->firstOrCreate([], [
            'tg_id' => $dto->tgId,
        ]);

        return $dto->fromModel($ignoreList);
    }

    public function getIgnoredByTgId(int $tgId): ?IgnoreListDTO
    {
        $ignoredtgId = IgnoreList::where('tg_id', $tgId)->first();
        if ($ignoredtgId) {
            return new IgnoreListDTO(
                $ignoredtgId->tg_id,
            );
        }

        return null;
    }
}
