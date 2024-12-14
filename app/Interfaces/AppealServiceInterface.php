<?php

namespace App\Interfaces;

use App\DTO\AppealDTO;

interface AppealServiceInterface
{
    public function createAppeal(array $appealDataArray): ?AppealDTO;
    public function getAppeal(int $id): ?AppealDTO;
    public function getAppeals(int $count, string $sort): ?array;
}
