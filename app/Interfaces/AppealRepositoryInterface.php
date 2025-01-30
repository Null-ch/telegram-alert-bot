<?php

namespace App\Interfaces;

use App\DTO\AppealDTO;
use App\Models\Appeal;

interface AppealRepositoryInterface
{
    public function create(AppealDTO $dto): ?AppealDTO;
    public function getLastAppeal(int|string $id): ?Appeal;
    public function getAppeal(int $id): ?AppealDTO;
    public function getAppeals(int $count, string $sort): ?array;
}
