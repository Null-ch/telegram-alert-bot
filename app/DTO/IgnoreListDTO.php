<?php

namespace App\DTO;

use App\Models\IgnoreList;
use App\Interfaces\DTOInterface;
use Illuminate\Support\Facades\Validator;

class IgnoreListDTO implements DTOInterface
{
    public function __construct(
        public string $tgId,
    )
    {
        $this->tgId = $tgId;
    }

    public function isValid(): bool
    {
        $validator = Validator::make(
            $this->toArray(),
            [
                'tg_id' => 'required|string',
            ]
        );

        return $validator->passes();
    }

    public function toArray(): array
    {
        return [
            'tg_id' => $this->tgId,
        ];
    }

    public function fromModel(IgnoreList $ignoreList): self
    {
        return new self(
            $ignoreList->tg_id,
        );
    }
}

