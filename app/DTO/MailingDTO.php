<?php

namespace App\DTO;

use App\Models\Mailing;

class MailingDTO
{
    public function __construct(
        public string $message,
        public string $account,
        public ?int $id = null,
    )
    {
        $this->message = $message;
        $this->account = $account;
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'account' => $this->account,
        ];
    }

    public function fromModel(Mailing $mailing): self
    {
        return new self(
            $mailing->message,
            $mailing->account,
            $mailing->id,
        );
    }
}
