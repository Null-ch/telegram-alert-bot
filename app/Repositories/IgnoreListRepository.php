<?php

namespace App\Repositories;

use App\DTO\IgnoreListDTO;
use App\Models\IgnoreList;

class IgnoreListRepository
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
}
