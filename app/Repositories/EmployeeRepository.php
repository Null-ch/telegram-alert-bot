<?php

namespace App\Repositories;

use App\DTO\EmployeeDTO;
use App\Models\Employee;
use App\Interfaces\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public Employee $employee;

    public function __construct(
        Employee $employee
    ) {
        $this->employee = $employee;
    }

    public function create(EmployeeDTO $dto): ?EmployeeDTO
    {
        $employee = $this->employee::where('tg_id', $dto->tgId)->firstOrCreate([], [
            'first_name' => $dto->firstName,
            'last_name' => $dto->lastName,
            'tag' => $dto->tag,
            'tg_id' => $dto->tgId,
        ]);

        return $dto->fromModel($employee);
    }

    public function update(int $id, EmployeeDTO $dto): ?EmployeeDTO
    {
        $employee = Employee::findOrFail($id);
        $employee->first_name = $dto->firstName;
        $employee->last_name = $dto->lastName;
        $employee->tag = $dto->tag;
        $employee->tg_id = $dto->tgId;
        $employee->save();

        return $dto->fromModel($employee);
    }

    public function getEmployee(int $id): ?EmployeeDTO
    {
        $employee = Employee::findOrFail($id);

        return new EmployeeDTO(
            $employee->first_name,
            $employee->last_name,
            $employee->tag,
            $employee->tg_id ? (int) $employee->tg_id : null,
            $employee->id,
        );
    }

    public function getEmployeeByTgId(int|string $id): ?EmployeeDTO
    {
        if ($employee = Employee::where('tg_id', $id)->first()) {
            return new EmployeeDTO(
                $employee->first_name,
                $employee->last_name,
                $employee->tag,
                $employee->tg_id ? (int) $employee->tg_id : null,
                $employee->id,
            );
        }

        return null;
    }
}
