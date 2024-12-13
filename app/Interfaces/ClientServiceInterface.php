<?php

namespace App\Interfaces;

use App\DTO\ClientDTO;

interface ClientServiceInterface
{
    public function createClient(array $clientData): ?ClientDTO;

    public function updateClient(int $clientId, array $clientData): ?ClientDTO;
    public function getClientById(int $id): ?ClientDTO;
}
