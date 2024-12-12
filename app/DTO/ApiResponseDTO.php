<?php

namespace App\DTO;

class ApiResponseDTO
{
    public string $statusCode;
    public bool $success;
    public mixed $data;
    public ?string $error;

    public function __construct(string $statusCode, bool $success, mixed $data, ?string $error = null)
    {
        $this->statusCode = $statusCode;
        $this->success = $success;
        $this->data = $data;
        $this->error = $error;
    }
}
