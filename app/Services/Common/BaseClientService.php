<?php

namespace App\Services\Common;

use Throwable;
use App\DTO\ClientDTO;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Repositories\ClientRepository;
use App\Interfaces\ClientServiceInterface;

class BaseClientService implements ClientServiceInterface
{
    public ClientRepository $clientRepository;

    public function __construct(
        ClientRepository $clientRepository
    ) {
        $this->clientRepository = $clientRepository;
    }

    public function createClient(array $clientDataArray): ?ClientDTO
    {
        try {
            $clientData = new ClientDTO(
                Arr::get($clientDataArray, 'fullName'),
                Arr::get($clientDataArray, 'tgId'),
            );

            return $this->clientRepository->create($clientData);
        } catch (Throwable $e) {
            Log::error("Error when create client: {$e->getMessage()}", $e->getTrace());
            return null;
        }
    }

    public function updateClient(int $clientId, array $clientDataArray): ?ClientDTO
    {
        try {
            $clientData = new ClientDTO(
                Arr::get($clientDataArray, 'fullName'),
                Arr::get($clientDataArray, 'tgId'),
            );

            return $this->clientRepository->update(
                $clientId,
                $clientData
            );
        } catch (Throwable $e) {
            Log::error("Error when update client: {$e->getMessage()}", $e->getTrace());
            return null;
        }
    }

    public function getClientById(int $id): ?ClientDTO
    {
        return $this->clientRepository->getClient($id);
    }

    public function getClientByTgId(int|string $id): ?ClientDTO
    {
        return $this->clientRepository->getClientByTgId($id);
    }
}
