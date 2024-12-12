<?php

namespace App\Interfaces;

use App\DTO\AppealDTO;
use App\Models\Appeal;

interface AppealRepositoryInterface
{
    public function create(AppealDTO $dto): ?Appeal;
    public function update(int $id, AppealDTO $dto): ?Appeal;
    public function getAppeal(int $id): ?Appeal;
}
