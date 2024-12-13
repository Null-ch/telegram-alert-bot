<?php

namespace App\Interfaces;

use App\DTO\ClientDTO;

interface ClientRepositoryInterface
{
    public function create(ClientDTO $dto): ?ClientDTO;
    public function update(int $id, ClientDTO $dto): ?ClientDTO;
    public function getClient(int $id): ?ClientDTO;
}
