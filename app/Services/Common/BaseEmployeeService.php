<?php

namespace App\Services\Common;

use Throwable;
use App\DTO\EmployeeDTO;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Repositories\EmployeeRepository;
use App\Interfaces\EmployeeServiceInterface;

class BaseEmployeeService implements EmployeeServiceInterface
{
    public EmployeeRepository $employeeRepository;

    public function __construct(
        EmployeeRepository $employeeRepository
    ) {
        $this->employeeRepository = $employeeRepository;
    }

    public function createEmployee(array $employeeDataArray): ?EmployeeDTO
    {
        try {
            $employeeData = new EmployeeDTO(
                Arr::get($employeeDataArray, 'firstName'),
                Arr::get($employeeDataArray, 'lastName'),
                Arr::get($employeeDataArray, 'tag'),
                Arr::get($employeeDataArray, 'tgId'),
            );

            return $this->employeeRepository->create($employeeData);
        } catch (Throwable $e) {
            Log::error("Error when create employee: {$e->getMessage()}", $e->getTrace());
            return null;
        }
    }

    public function updateEmployee(int $employeeId, array $employeeDataArray): ?EmployeeDTO
    {
        try {
            $employeeData = new EmployeeDTO(
                Arr::get($employeeDataArray, 'firstName'),
                Arr::get($employeeDataArray, 'lastName'),
                Arr::get($employeeDataArray, 'tag'),
                Arr::get($employeeDataArray, 'tgId'),
            );

            return $this->employeeRepository->update(
                $employeeId,
                $employeeData
            );
        } catch (Throwable $e) {
            Log::error("Error when update employee: {$e->getMessage()}", $e->getTrace());
            return null;
        }
    }

    public function getEmployeeById(int $id): ?EmployeeDTO
    {
        return $this->employeeRepository->getEmployee($id);
    }

    public function getEmployeeByTgId(int|string $id): ?EmployeeDTO
    {
        return $this->employeeRepository->getEmployeeByTgId($id);
    }
}


