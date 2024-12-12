<?php

namespace App\Interfaces;

use App\DTO\ClientDTO;
use App\Models\Client;

interface ClientRepositoryInterface
{
    public function create(ClientDTO $dto): ?Client;
    public function update(int $id, ClientDTO $dto): ?Client;
    public function getClient(int $id): ?Client;
}
