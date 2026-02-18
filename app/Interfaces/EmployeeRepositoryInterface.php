<?php

namespace App\Interfaces;

use App\DTO\EmployeeDTO;

interface EmployeeRepositoryInterface
{
    public function create(EmployeeDTO $dto): ?EmployeeDTO;

    public function update(int $id, EmployeeDTO $dto): ?EmployeeDTO;

    public function getEmployee(int $id): ?EmployeeDTO;
}
