<?php

namespace App\Interfaces;

use App\DTO\AppealDTO;

interface AppealServiceInterface
{
    public function createAppeal(array $appealDataArray): ?AppealDTO;
}
