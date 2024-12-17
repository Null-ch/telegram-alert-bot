<?php

namespace App\Interfaces;

use App\DTO\IgnoreListDTO;

interface IgnoreListRepositoryInterface
{
    public function create(IgnoreListDTO $dto): IgnoreListDTO;

    public function getIgnoredByTgId(int $id): ?IgnoreListDTO;
}
