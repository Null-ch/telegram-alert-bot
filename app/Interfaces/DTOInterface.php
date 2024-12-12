<?php

namespace App\Interfaces;

interface DTOInterface
{
    public function isValid(): bool;
    public function toArray(): array;
}
