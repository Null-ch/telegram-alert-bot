<?php

namespace App\Interfaces;

use App\DTO\EmployeeDTO;

interface EmployeeServiceInterface
{
    public function createEmployee(array $employeeData): ?EmployeeDTO;

    public function updateEmployee(int $employeeId, array $employeeData): ?EmployeeDTO;

    public function getEmployeeById(int $id): ?EmployeeDTO;
}


