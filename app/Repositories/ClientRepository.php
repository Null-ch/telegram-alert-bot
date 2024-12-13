<?php

namespace App\Repositories;

use App\DTO\ClientDTO;
use App\Models\Client;
use App\Interfaces\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public Client $client;

    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }

    public function create(ClientDTO $dto): ClientDTO
    {
        $client = $this->client::where('tg_id', $dto->tgId)->firstOrCreate([], [
            'full_name' => $dto->fullName,
            'tg_id' => $dto->tgId,
        ]);

        return $dto->fromModel($client);
    }

    public function update(int $id, ClientDTO $dto): ClientDTO
    {
        $client = Client::findOrFail($id);
        $client->full_name = $dto->fullName;
        $client->tg_id = $dto->tgId;
        $client->save();

        return $dto->fromModel($client);
    }

    public function getClient(int $id): ?ClientDTO
    {
        $client = Client::findOrFail($id);
        $clientDTO = new ClientDTO(
            $client->full_name,
            $client->tg_id,
            $client->id,
        );

        return $clientDTO;
    }

    public function getClientByTgId(int|string $id): ?ClientDTO
    {
        if ($client = Client::where('tg_id', $id)->first()) {
            return new ClientDTO(
                $client->full_name,
                $client->tg_id,
                $client->id,
            );
        }

        return null;
    }
}
