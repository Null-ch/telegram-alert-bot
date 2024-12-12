<?php

namespace App\Interfaces;

use App\DTO\ApiResponseDTO;
use Illuminate\Http\Request;

interface TelegramServiceInterface
{
    public function setWebhook(string $prefix): ApiResponseDTO;
    public function removeWebhook(string $prefix): ApiResponseDTO;
    public function getDefaultCallback(array $params): string;
}
