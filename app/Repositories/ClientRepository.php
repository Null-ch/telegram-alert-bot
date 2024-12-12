<?php

namespace App\Repositories;

use App\DTO\ClientDTO;
use App\Models\Client;
use App\Interfaces\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public function create(ClientDTO $dto): Client
    {
        $client = new Client();
        $client->full_name = $dto->fullName;
        $client->tg_id = $dto->tgId;
        $client->channel_id = $dto->channelId;
        $client->save();

        return $client;
    }

    public function update(int $id, ClientDTO $dto): Client
    {
        $client = Client::findOrFail($id);
        $client->full_name = $dto->fullName;
        $client->tg_id = $dto->tgId;
        $client->channel_id = $dto->channelId;
        $client->save();

        return $client;
    }

    public function getClient(int $id): ?Client
    {
        return Client::findOrFail($id);
    }
}
