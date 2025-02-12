<?php

namespace App\Interfaces;

use App\DTO\IgnoreListDTO;

interface IgnoreListServiceInterface
{
    public function create(IgnoreListDTO $dto): IgnoreListDTO;

    public function getIgnoredByTgId(int $tgId): ?IgnoreListDTO;
}
