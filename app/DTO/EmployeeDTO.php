<?php

namespace App\DTO;

use App\Models\Employee;
use App\Interfaces\DTOInterface;
use Illuminate\Support\Facades\Validator;

class EmployeeDTO implements DTOInterface
{
    public function __construct(
        public ?string $firstName,
        public ?string $lastName,
        public ?string $tag,
        public ?int $tgId,
        public ?int $employeeId = null,
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->tag = $tag;
        $this->tgId = $tgId;
        $this->employeeId = $employeeId;
    }

    public function isValid(): bool
    {
        $validator = Validator::make(
            $this->toArray(),
            [
                'first_name' => 'nullable|string',
                'last_name' => 'nullable|string',
                'tag' => 'nullable|string',
                'tg_id' => 'nullable|integer',
            ]
        );

        return $validator->passes();
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'tag' => $this->tag,
            'tg_id' => $this->tgId,
        ];
    }

    public function fromModel(Employee $employee): self
    {
        return new self(
            $employee->first_name,
            $employee->last_name,
            $employee->tag,
            $employee->tg_id ? (int) $employee->tg_id : null,
            $employee->id,
        );
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function getTgId(): ?int
    {
        return $this->tgId;
    }

    public function getEmployeeId(): ?int
    {
        return $this->employeeId;
    }
}
