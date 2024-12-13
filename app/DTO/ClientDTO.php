<?php

namespace App\DTO;

use App\Models\Client;
use App\Interfaces\DTOInterface;
use Illuminate\Support\Facades\Validator;

class ClientDTO implements DTOInterface
{
    public function __construct(
        public string $fullName,
        public int $tgId,
        public ?int $clientId = null,
    )
    {
        $this->fullName = $fullName;
        $this->tgId = $tgId;
        $this->clientId = $clientId;
    }

    public function isValid(): bool
    {
        $validator = Validator::make(
            $this->toArray(),
            [
                'full_name' => 'required|string',
                'tg_id' => 'required|integer',
            ]
        );

        return $validator->passes();
    }


    public function toArray(): array
    {
        return [
            'full_name' => $this->fullName,
            'tg_id' => $this->tgId,
        ];
    }

    public function fromModel(Client $client): self
    {
        return new self(
            $client->full_name,
            $client->tg_id,
            $client->id,
        );
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getTgId(): int
    {
        return $this->tgId;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }
}
