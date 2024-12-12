<?php

namespace App\DTO;

use App\Interfaces\DTOInterface;
use Illuminate\Support\Facades\Validator;

class AppealDTO implements DTOInterface
{
    public function __construct(
        public string $text,
        public string $chatType,
        public int $userId,
        public int $messageId,
    )
    {
    }

    public function isValid(): bool
    {
        $validator = Validator::make(
            $this->toArray(),
            [
                'text' => 'required|string',
                'chat_type' => 'required|string',
                'client_id' => 'required|integer',
                'message_id' => 'required|integer',
            ]
        );

        return $validator->passes();
    }


    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'chat_type' => $this->chatType,
            'client_id' => $this->userId,
            'message_id' => $this->messageId,
        ];
    }
}