<?php

namespace App\DTO;

use App\Interfaces\DTOInterface;
use Illuminate\Support\Facades\Validator;

class ClientDTO implements DTOInterface
{
    public function __construct(
        public string $fullName,
        public int $tgId,
        public int $channelId
    )
    {
    }

    public function isValid(): bool
    {
        $validator = Validator::make(
            $this->toArray(),
            [
                'full_name' => 'required|string',
                'tg_id' => 'required|integer',
                'channel_id' => 'required|integer',
            ]
        );

        return $validator->passes();
    }


    public function toArray(): array
    {
        return [
            'full_name' => $this->fullName,
            'tg_id' => $this->tgId,
            'channel_id' => $this->channelId,
        ];
    }
}
